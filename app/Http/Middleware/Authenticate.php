<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
                return response()->error(Response::HTTP_UNAUTHORIZED);
            }
            return redirect()->route('loginForm');
        }

        return $next($request);
    }


    private function jwtAuth($request, Closure $next)
    {
//        try {
//            \JWTAuth::parseToken()->authenticate();
//        } catch (TokenExpiredException $e) {
//            return response()->error($e->getStatusCode(), $e->getMessage());
//        } catch (TokenInvalidException $e) {
//            return response()->error(Response::HTTP_FORBIDDEN, $e->getMessage());
//        } catch (JWTException $e) {
//            return response()->error($e->getStatusCode(), $e->getMessage());
//        }

        $token = null;
        $message = '';
        try {
            $token = \JWTAuth::parseToken();
        } catch (JWTException $e) {
            $token = null;
            $message = $e->getMessage();
        }

        if (null === $token) {
            throw new UnauthorizedHttpException('', $message);
        }

        try {
            $token->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->error($e->getStatusCode(), $e->getMessage());
        } catch (TokenInvalidException $e) {
            return response()->error(Response::HTTP_FORBIDDEN, $e->getMessage());
        }

        return $next($request);
    }
} 
