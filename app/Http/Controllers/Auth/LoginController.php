<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * @return Response
     * @throws \LogicException
     */
    public function getLogin()
    {
        return response()->render('auth.login', [
            'email'    => null,
            'password' => null
        ]);
    }

    /**
     * @param string $phone
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getOtpCode(string $phone): Response
    {
        $user = User::findByPhone($phone);

        if (null === $user) {
            return \response()->error(Response::HTTP_NOT_FOUND, 'User with phone ' . $phone . ' not found.');
        }

        /** @var OtpAuth $otpAuth */
        $otpAuth = app(OtpAuth::class);
        $otpAuth->generateCode($user->phone);

        return \response()->render('auth.sms.success', ['phone_number' => $user->phone, 'code' => null],
            Response::HTTP_ACCEPTED, route('register'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function logout()
    {
        auth()->logout();

        return request()->wantsJson()
            ? response()->render('', '', Response::HTTP_NO_CONTENT)
            : redirect()->route('home');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokenRefresh()
    {
        /** @var JWTAuth $jwtAuth */
        $jwtAuth = app('tymon.jwt.auth');

        try {
            $token = $jwtAuth->refresh();
        } catch (TokenInvalidException $e) {
            return \response()->error(Response::HTTP_UNAUTHORIZED, 'The token is invalid');
        }

        return response()->json(compact('token'));
    }

    /**
     * @param LoginRequest    $request
     * @param ResponseFactory $response
     *
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function login(LoginRequest $request, ResponseFactory $response)
    {
        $user            = null;
        $defaultProvider = 'users';

        $credentials = $request->all();

        /** @var AuthManager $auth */
        $auth = app('auth');

        foreach (config('auth.guards') as $guardName => $config) {
            try {
                $validated = auth($guardName)->validate($credentials);
            } catch (QueryException $queryException) {
                $validated = false;
            }

            if (false === $validated) {
                continue;
            }

            $providerName = $config['provider'] ?? $defaultProvider;
            $provider     = $auth->createUserProvider($providerName);
            $user         = $provider->retrieveByCredentials($credentials);

            break;
        }

        return $request->wantsJson()
            ? $this->postLoginJwt($user, $response)
            : $this->postLoginSession($user, $response);
    }

    /**
     * @param Authenticatable $user
     * @param ResponseFactory $response
     *
     * @return Response
     */
    private function postLoginJwt(Authenticatable $user, ResponseFactory $response): Response
    {
        /** @var JWTAuth $jwtAuth */
        $jwtAuth = app('tymon.jwt.auth');

        $token = $jwtAuth->fromUser($user);

        return $response->render('', compact('token'));
    }

    /**
     * @param Authenticatable $user
     * @param ResponseFactory $response
     *
     * @return Response
     *
     */
    private function postLoginSession(Authenticatable $user, ResponseFactory $response)
    {
        auth('web')->login($user);

        return $response->redirectTo(request()->get('redirect_to', '/'));
    }
}
