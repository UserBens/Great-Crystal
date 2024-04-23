<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
   public function index()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'dashboard',
         ]);
         session()->flash('preloader', true);
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }

   public function changeMyPassword()
   {
      try {
         //code...
         session()->flash('page',  $page = (object)[
            'page' => 'admin',
            'child' => 'change-password',
         ]);
         return view('components.change-password');
         
      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function actionChangeMyPassword(Request $request)
   {
      
      session()->flash('page',  $page = (object)[
         'page' => 'admin',
         'child' => 'change-password',
      ]);

      session()->flash('preloader', true);

      try {
         //code...

         

         $user = Auth::user();

         if(!$user)
         {
            return redirect('/');
         }

         $rules = $request->only('password', 'reinputPassword');
         
         $validator = Validator::make($rules, [
            'password' => 'required|min:5', 
            'reinputPassword' => 'required|min:5',
         ]);

         if($request->password !== $request->reinputPassword)
         {
            
            return redirect('/admin/user/change-password')->withErrors([ 
               'password' => 'Make sure your input password is the same !!!',
               'reinputPassword' => 'Make sure your input password is the same !!!',
               ])->withInput($rules);
         }
         
         if($validator->fails())
         {
            
            return redirect('/admin/user/change-password')->withErrors($validator->messages())->withInput($rules);
         }

         User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
         ]);

         session()->flash('success.update.password');
         return redirect('/');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }

   
}