<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkAccounting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            session()->flash('errors', 'Invalid Credentials');
            return redirect('/')->withErrors([
               'credentials' => 'Invalid credentials, please login first !!!'
            ]);
         } else {
            $user = Auth::user();
            if($user->role === 'admin')
            {
               session()->flash('role', 'admin');
               return redirect('/admin/dashboard');
            } 
         }
         $user = Auth::user();
         session()->flash('role', $user->role);
         return $next($request);
    }
}
