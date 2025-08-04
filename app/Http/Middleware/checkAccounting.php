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
      if (!Auth::check()) {
         session()->flash('errors', 'Invalid Credentials');
         return redirect('/')->withErrors([
            'credentials' => 'Invalid credentials, please login first !!!'
         ]);
      }

      $user = Auth::user();

      
      // ✅ Hanya accounting & superadmin yang boleh lewat
      if (!in_array($user->role, ['accounting', 'superadmin'])) {
         return redirect('/admin/dashboard')
            ->with('error', 'Unauthorized access');
      }
      
      // Simpan role di session
      session()->flash('role', $user->role);

      return $next($request);
   }
}
