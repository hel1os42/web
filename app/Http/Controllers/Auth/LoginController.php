<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use \Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return response()->render('auth.login', [
            'data' => [
                'email'    => null,
                'password' => null
            ]
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
                    trans('errors.jwt_exception') . $e->getMessage()
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
                    trans('errors.jwt_exception') . $e->getMessage()
                );
            }
            return response()->render('', compact('logout'));
        }

        auth()->logout();
        return redirect()->route('home');
    }
}
