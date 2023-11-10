<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

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
                // CHECK ROLE
                if (Auth::user()->level == 'admin') {
                    return redirect(RouteServiceProvider::HOME_ADMIN);
                } else if (Auth::user()->level == 'redaksi') {
                    return redirect(RouteServiceProvider::HOME_REDAKSI);
                } else if (Auth::user()->level == 'reporter') {
                    return redirect(RouteServiceProvider::HOME_REPORTER);
                } else if (Auth::user()->level == 'jurnalis') {
                    return redirect(RouteServiceProvider::HOME_JURNALIS);
                } else if (Auth::user()->level == 'user') {
                    return redirect(RouteServiceProvider::HOME_USER);
                }
            }
        }

        return $next($request);
    }
}
