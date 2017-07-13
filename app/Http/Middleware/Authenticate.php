<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->wantsJson()) {
            return $this->jwtAuth($request, $next);
        }

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Не авторизован.', 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }


    private function jwtAuth($request, Closure $next)
    {
        try {
            \JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'errors' => [
                    'token_expired' => $e->getMessage(),
                ]
            ], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json([
                'errors' => [
                    'token_invalid' => $e->getMessage(),
                ]
            ], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'jwt_exception' => $e->getMessage(),
                ]
            ], $e->getStatusCode());
        }

        return $next($request);
    }

} 