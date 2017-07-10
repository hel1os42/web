<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
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
