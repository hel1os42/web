<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use OmniSynapse\CoreService\CoreService;
use \Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return response()->render('auth.login');
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
                    return response()->render(null, [
                        'errors' => [
                            'invalid_email_or_password' => null,
                        ]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            } catch (JWTException $e) {
                return response()->render(null, [
                    'errors' => [
                        'failed_to_create_token' => $e->getMessage(),
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->render(null, compact('token'));
        }

        $attempt = \Auth::attempt($credentials, false);

        if (false === $attempt) {
            session()->flash('message', trans('auth.failed'));
            return redirect()->route('getLogin');
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
                return response()->render(null, [
                    'errors' => [
                        'jwt_exception' => $e->getMessage(),
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return response()->render(null, compact('logout'));
        }

        auth()->logout();
        return redirect('/');
    }
}
