<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;




class SuperAdminController extends Controller
{
   public function index()
   {

      session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
      try {
         //code...
         session()->flash('preloader', false);
         return view('components.dashboard');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }

   public function getUser()
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = Auth::user();

         $data = User::where('id', '!=', '1')->where('id', '!=', $user->id)->get();
         return view('components.super.data-user')->with('data', $data);

         

      } catch (Exception $err) {
         
         return dd($err);
      }
   }


   public function getById($id)
   {
      try {
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         //code...
         $user = User::where('id', $id)->first();
         
         return $user;  

      } catch (Exception $th) {
         return dd($th);
      }
   }


   public function changePassword(Request $request, $id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         $rules = $request->only('password', 'reinputPassword');
         
         $validator = Validator::make($rules, [
            'password' => 'required|min:5', 
            'reinputPassword' => 'required|min:5',
         ]);

         if($request->password !== $request->reinputPassword)
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', 'Make sure your input password is the same !!!');
            session()->flash('error.password', false);
            
            return redirect('/admin/user');
         }
         
         if($validator->fails())
         {
            session()->flash('password.success', false);
            session()->flash('error.type.password', false);
            session()->flash('error.password', $validator->messages());
            
            return redirect('/admin/user');
         }
         
         
         User::where('id', $id)->update([
            'password' => Hash::make($request->password),
         ]);
         
         session()->flash('password.success');
         session('error.type.password', false);
         session('error.password', false);
         return redirect('/admin/user');
      } catch (Exception $th) {
         //throw $th;
         return dd($th);
      }
   }


   public function registerUser()
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         
         return view('components.super.register-user');
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function registerUserAction(Request $request)
   {
      try {
         //code...
         session()->flash('preloader');
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);
         $credentials = $request->only(['username', 'password', 'role']);


         $validator = Validator::make($credentials, [
            'username' => 'required|unique:users|string',
            'password' => 'required|min:5|string',
            'role' => 'required|string|in:superadmin,admin,accounting'
         ]);
         
         if($validator->fails())
         {
            return redirect('/admin/user/register-user')->withErrors($validator->messages())->withInput($credentials);
         }

         if($request->password !== $request->reinputPassword)
         {
            

            $error = [
               'password' => ['password does not match'],
               'reinputPassword' => ['password does not match']
            ];

            return redirect('/admin/user/register-user')->withErrors($error)->withInput($credentials);
         }

         User::create($credentials);
         
         session()->flash('register.success');
         return redirect('/admin/user');
      } catch (Exception $err) {
         return dd($err);
      }
   }

   public function deleteUser($id)
   {
      try {
         //code...
         session()->flash('preloader', false);
         session()->flash('page',  $page = (object)[
            'page' => 'user',
            'child' => 'database user',
         ]);

         if(!User::where('id', $id)->first())
         {
            return response()->json(['errors' => 'user id not found!']);
         }

         User::where('id', $id)->delete();

         return response()->json(['status' => 201, 
         'response' => 'Delete user success'
      ]);
      } catch (Exception $err) {
         //throw $th;

         return dd($err);
      }
   }
}