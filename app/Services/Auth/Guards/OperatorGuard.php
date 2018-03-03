<?php

namespace App\Services\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class OperatorGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    protected $session;

    protected $name;

    protected $request;

    public function logout()
    {
        $this->jwtAuth->invalidate();
    }

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     */
    public function __construct($name, UserProvider $provider, Session $session)
    {
        $this->name     = $name;
        $this->provider = $provider;
        $this->jwtAuth  = app('tymon.jwt.auth');
        $this->session  = $session;
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
        if (is_null($this->user)) {
            $user       = $this->provider->retrieveById($this->id());
            $this->user = $user;
        }

        return $this->user;
    }

    /**
     * Get the id currently authenticated user.
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @return string
     */
    public function id()
    {
        $authId = $this->session->get($this->getName());
        if (is_null($authId)) {
            $token  = $this->jwtAuth->getToken();
            $authId = false !== $token ? $this->jwtAuth->getPayload($token)->get('sub') : null;
        }

        return $authId;
    }

    public function login(Authenticatable $user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        $this->setUser($user);
    }

    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);

        $this->session->migrate(true);
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

    public function getName()
    {
        return 'login_' . $this->name . '_' . sha1(static::class);
    }
}
