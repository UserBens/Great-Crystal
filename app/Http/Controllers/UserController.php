<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

   public function login()
   {
      try {
         //code...
         session()->flash('preloader', true);
         Auth::logout();
         return view('layouts.login');
      } catch (Exception $err) {
         
         return dd($err);
      }
   }
   
   
   public function actionLogin(Request $request)
   {
      try {
         //code...
         session()->flash('preloader', true);
         session()->flash('page',  $page = (object)[
            'page' => 'students',
            'child' => 'dashboard',
         ]);
         $credentials = $request->only('username', 'password');

         $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required',
         ],
         [
            'username.required' => 'input username !!!',
            'password.required' => 'input password !!!'
         ]);

         if($validator->fails())
         {
            // return dd($validator->messages());
            return redirect('/')->withErrors($validator->messages())->withInput($credentials);
         }

         $check = Auth::attempt($credentials);  

         if(!$check)
         {
            return redirect()->back()->withErrors(['invalid' => 'invalid username/password'])->withInput($credentials);
         }
         

         return redirect('admin/dashboard/');

      } catch (Exception $err) {
         
         return dd($err);
      }

      
   }

   

   public function logout()
   {
      try {
         //code...
         Auth::logout();
         session()->flash('preloader');
         // return redirect('/');
         return (object) [
            'success' => true,
         ];
      } catch (Exception $err) {
         //throw $th;
         return (object) [
            'success' => false,
         ];
      }
   }

}