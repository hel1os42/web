<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 09.10.2017
 * Time: 12:36
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    use ThrottlesLogins;

    /**
     * @var int
     */
    public $maxAttempts = 1;

    /**
     * @var int
     */
    public $decayMinutes;

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

        $this->decayMinutes = config('auth.throttle.ttl', 1);

        parent::__construct($auth);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  LoginRequest $request
     * @return mixed
     */
    protected function sendFailedLoginResponse(LoginRequest $request)
    {
        if ($request->expectsJson()) {
            $statusCode = $request->isAuthorizeByIdentityAccessToken()
                ? Response::HTTP_NOT_FOUND
                : Response::HTTP_UNAUTHORIZED;

            return \response()->error($statusCode, trans('auth.failed'));
        }

        $errors = ['email' => trans('auth.failed'),
            'alias' => trans('auth.failed')];

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors($errors);
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return mb_strtolower($request->route()->parameter('phone_number').'|'.$request->ip());
    }

    /**
     * @return string
     */
    public function username()
    {
        return 'phone';
    }
}
