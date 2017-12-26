<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param         $request
     * @param Closure $next
     * @param null    $guard
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     * @throws \InvalidArgumentException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect()->route('profile');
        }

        return $next($request);
    }
}
