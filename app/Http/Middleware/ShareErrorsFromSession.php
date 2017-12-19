<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\View\Middleware\ShareErrorsFromSession as BaseValidator;

/**
 * Class ShareErrorsFromSession
 * NS: App\Http\Middleware
 */
class ShareErrorsFromSession extends BaseValidator
{
    public function handle($request, Closure $next)
    {
        if (!$request->hasCookie(config('session.cookie'))) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
