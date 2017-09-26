<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse as BaseVerifier;

/**
 * Class AddQueuedCookiesToResponse
 * NS: App\Http\Middleware
 */
class AddQueuedCookiesToResponse extends BaseVerifier
{
    public function handle($request, Closure $next)
    {
        if (!$request->hasCookie(config('session.cookie')) && $request->wantsJson()) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }

}
