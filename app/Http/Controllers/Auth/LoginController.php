<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LoginController extends AuthController
{
    use ThrottlesLogins;

    /**
     * @return Response
     */
    public function getLogin()
    {
        return \response()->render('auth.login', [
            'email'    => null,
            'password' => null
        ]);
    }

    /**
     * @param OtpAuth $otpAuth
     * @param string  $phone
     *
     * @return Response
     */
    public function getOtpCode(OtpAuth $otpAuth, string $phone): Response
    {
        $user = $this->userRepository->findByPhone($phone);

        if (null === $user) {
            return \response()->error(Response::HTTP_NOT_FOUND, 'User with phone ' . $phone . ' not found.');
        }

        /** @var OtpAuth $otpAuth */
        $otpAuth->generateCode($user->phone);

        return \response()->render('auth.sms.success', ['phone_number' => $user->phone, 'code' => null],
            Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * @return Response
     */
    public function logout()
    {
        $this->auth->guard()->logout();

        return \request()->wantsJson()
            ? \response()->render('', '', Response::HTTP_NO_CONTENT)
            : \redirect()->route('home');
    }

    /**
     * @return Response
     */
    public function tokenRefresh()
    {
        try {
            $token = $this->jwtAuth->refresh();
        } catch (TokenInvalidException $e) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, 'The token is invalid');
        }

        return \response()->json(compact('token'));
    }

    /**
     * @param LoginRequest    $request
     * @param ResponseFactory $response
     * @param Session         $session
     *
     * @return Response
     *
     */
    public function login(LoginRequest $request, Session $session)
    {
        $user            = null;
        $defaultProvider = 'users';

        $credentials = $request->credentials();

        foreach (\config('auth.guards') as $guardName => $config) {
            try {
                $validated = $this->auth->guard($guardName)->validate($credentials);
            } catch (QueryException $queryException) {
                $validated = false;
            }

            if (false === $validated) {
                continue;
            }

            $providerName = $config['provider'] ?? $defaultProvider;
            $provider     = $this->auth->createUserProvider($providerName);
            $user         = $provider->retrieveByCredentials($credentials);

            break;
        }

        if (null === $user) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, trans('auth.failed'));
        }

        $session->migrate(true);

        return $request->wantsJson()
            ? $this->postLoginJwt($user)
            : $this->postLoginSession($user);
    }

    /**
     * @param Authenticatable $user
     *
     * @return Response
     */
    private function postLoginJwt(Authenticatable $user): Response
    {
        $token = $this->jwtAuth->fromUser($user);

        return \response()->render('', \compact('token'));
    }

    /**
     * @param Authenticatable $user
     *
     * @return Response
     */
    private function postLoginSession(Authenticatable $user)
    {
        $this->auth->guard('web')->login($user);

        return \response()->redirectTo(\request()->get('redirect_to', '/'));
    }
}
