<?php

namespace App\Services\Auth\Guards;

use App\Repositories\OperatorRepository;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Tymon\JWTAuth\JWTAuth;

class OperatorGuard extends JwtGuard
{
    use GuardHelpers;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    public function logout()
    {
        $this->jwtAuth->invalidate();
    }

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
        $this->jwtAuth  = app('tymon.jwt.auth');
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     *
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     */
    public function user()
    {
        if (is_null($this->user) && false !== $this->jwtAuth->getToken()) {
            $user       = $this->provider->retrieveById($this->id());
            $this->user = $user;
        }

        return $this->user;
    }

    /**
     * Get the id currently authenticated user.
     *
     * @return string
     */
    public function id()
    {
        $token = $this->jwtAuth->getToken();
        $id    = $this->jwtAuth->getPayload($token)->get('sub');

        return $id;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user, $credentials);

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed $user
     * @param  array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}
