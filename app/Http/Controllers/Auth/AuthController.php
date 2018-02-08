<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 09.10.2017
 * Time: 12:36
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var JWTAuth
     */
    protected $jwtAuth;

    /**
     * AuthController constructor.
     *
     * @param UserRepository $userRepository
     * @param JWTAuth        $jwtAuth
     * @param AuthManager    $auth
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(UserRepository $userRepository, JWTAuth $jwtAuth, AuthManager $auth)
    {
        $this->userRepository = $userRepository;
        $this->jwtAuth        = $jwtAuth;

        parent::__construct($auth);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, trans('auth.failed'));
        }

        $errors = ['email' => trans('auth.failed')];

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors($errors);
    }
}
