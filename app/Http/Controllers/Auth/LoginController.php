<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;

        try {
            if (false === $token = \JWTAuth::attempt($credentials)) {
                return response()->json([
                    'errors' => [
                        'invalid_email_or_password' => null,
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'failed_to_create_token' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($request->wantsJson()) {
            return response()->json(compact('token'));
        }

        redirect('/');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            \JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'jwt_exception' => $e->getMessage(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (false === $request->wantsJson()) {
            redirect('/');
        }
    }
}
