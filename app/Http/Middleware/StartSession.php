<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession as BaseVerifier;

/**
 * Class StartSession
 * NS: App\Http\Middleware
 */
class StartSession extends BaseVerifier
{
    public function handle($request, Closure $next)
    {
        if (!$request->hasCookie(config('session.cookie')) && $request->wantsJson()) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
