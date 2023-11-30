<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::user()->isUser()) {
                    return redirect('/home');
                } elseif (Auth::user()->isAdmin()) {
                    return redirect('admin/dashboard');
                }

                elseif (Auth::user()->isSuperAdmin()) {
                    return redirect('superadmin/dashboard');
                }

               
                
            }
        }

        return $next($request);
    }
}
