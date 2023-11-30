<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the authenticated user has the required role
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            

            // Check user role and redirect accordingly
            switch ($userRole) {
                case 'user':
                    return $next($request); // Allow access to the home route
                case 'admin':
                    return redirect()->route('admin.dashboard'); // 
        
                 case 'superadmin':
                return redirect()->route('superadmin.dashboard'); 
            }
        }

        // Redirect or handle unauthorized access
        return redirect('/login')->with('error', 'Unauthorized access');
    }
}
