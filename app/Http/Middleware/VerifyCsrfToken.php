<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'auth/login',
    ];

    public function handle($request, Closure $next)
    {
        if (auth('jwt')->check() || !$request->hasCookie(config('session.cookie')) && $request->wantsJson()) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
