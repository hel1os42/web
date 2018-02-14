<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\PlaceRepository;
use App\Repositories\UserRepository;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends AuthController
{
    use ThrottlesLogins;

    public function __construct(PlaceRepository $placeRepository, UserRepository $userRepository, JWTAuth $jwtAuth, AuthManager $auth)
    {
        $this->placeRepository = $placeRepository;
        $this->userRepository  = $userRepository;
        $this->jwtAuth         = $jwtAuth;

        parent::__construct($userRepository, $jwtAuth, $auth);
    }

    /**
     * @return Response
     */
    public function getLogin()
    {
        return $this->auth->user()
            ? \response()->redirectTo(route('home'))
            : \response()->render('auth.login', [
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

        if (false === $this->jwtAuth->getToken()) {
            $this->user()->leaveImpersonation();
        }

        $this->auth->guard()->logout();

        return \request()->wantsJson()
            ? \response()->render('', '', Response::HTTP_NO_CONTENT)
            : \redirect()->route('login');
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
     * @param LoginRequest $request
     * @param Session      $session
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function login(LoginRequest $request, Session $session)
    {
        $user            = null;
        $defaultProvider = $request->input('provider') ?? 'users';
        $credentials     = $request->credentials();

        unset($credentials['provider']);
        $credentials['place_uuid'] = $this->placeRepository->findIdByAlias($credentials['alias'])->id;
        unset($credentials['alias']);

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
            return $this->sendFailedLoginResponse($request);
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
     * @throws \LogicException
     */
    private function postLoginJwt(Authenticatable $user): Response
    {
        $token = $this->jwtAuth->fromUser($user);

        return \response()->render('', \compact('token'));
    }

    /**
     * @param Authenticatable $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \LogicException
     */
    private function postLoginSession(Authenticatable $user)
    {
        $this->auth->guard('web')->login($user);

        return \response()->redirectTo(route('home'));
    }

    /**
     * @param string       $uuid
     * @param UrlGenerator $urlGenerator
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function impersonate(string $uuid, UrlGenerator $urlGenerator)
    {
        $user = $this->userRepository->find($uuid);

        $this->authorize('impersonate', $user);

        if (false !== $this->jwtAuth->getToken()) {
            $token = $this->jwtAuth->fromUser($user,
                [config('laravel-impersonate.session_key') => $this->user()->getKey()]);

            return \response()->render('', \compact('token'));
        }

        $this->user()->impersonate($user);

        session()->put('impersonate_last_url', $urlGenerator->previous());

        return \request()->wantsJson()
            ? \response()->render('', $this->user()->toArray())
            : \response()->redirectTo(route('home'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|Response
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function stopImpersonate()
    {
        $this->user()->leaveImpersonation();

        return \request()->wantsJson()
            ? \response()->render('', [])
            : \response()->redirectTo(\request()->session()->get('impersonate_last_url'));
    }
}
