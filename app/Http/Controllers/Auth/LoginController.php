<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return response()->render('auth.login', [
            'email'    => null,
            'password' => null
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Redirect
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($request->wantsJson()) {
            $token = null;

            try {
                if (false === $token = \JWTAuth::attempt($credentials)) {
                    return response()->error(
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        trans('errors.invalid_email_or_password')
                    );
                }
            } catch (JWTException $e) {
                return response()->error(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $e->getMessage()
                );
            }

            return response()->render('', compact('token'));
        }

        $attempt = \Auth::attempt($credentials, false);

        if (false === $attempt) {
            session()->flash('message', trans('auth.failed'));
            return redirect()->route('loginForm');
        }

        return redirect(request()->get('redirect_to', '/'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|Redirect
     */
    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            try {
                $logout = \JWTAuth::parseToken()->invalidate();
            } catch (JWTException $e) {
                return response()->error(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $e->getMessage()
                );
            }
            return response()->render('', compact('logout'));
        }

        auth()->logout();
        return redirect()->route('home');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenRefresh()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            throw new BadRequestHttpException('Token not provided');
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }

        return response()->json(compact('token'));
    }
}
