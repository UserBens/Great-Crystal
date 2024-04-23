<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if(!Auth::check()){
         session()->flash('error', 'please login first !!!');
         return redirect('/');
      } else {
         $user = Auth::user();
         
         if($user->role != 'superadmin')
         {
            
            session()->flash('role', $user->role);
            return redirect('/admin/dashboard');
         } 
      }
      
      session()->flash('role', 'superadmin');
      return $next($request);
    }
}