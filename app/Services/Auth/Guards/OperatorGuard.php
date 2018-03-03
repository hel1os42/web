<?php

namespace App\Services\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class OperatorGuard extends SessionGuard implements StatefulGuard
{
    use GuardHelpers;
    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    protected $session;

    protected $name;

    /**
     * @throws \RuntimeException
     * @throws \Tymon\JWTAuth\Exceptions\JWTException
     * @throws \Tymon\JWTAuth\Exceptions\TokenBlacklistedException
     */
    public function logout()
    {
        if(false !== $this->jwtAuth->getToken()) {
            $this->jwtAuth->invalidate();
        }

        parent::logout();
    }

    /**
     * OperatorGuard constructor.
     *
     * @param              $name
     * @param UserProvider $provider
     * @param Session      $session
     * @param Request|null $request
     */
    public function __construct($name, UserProvider $provider, Session $session, Request $request = null)
    {
        $this->name     = $name;
        $this->provider = $provider;
        $this->jwtAuth  = app('tymon.jwt.auth');
        $this->session  = $session;

        parent::__construct($name, $provider, $session, $request);
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

        return parent::user();
    }

    /**
     * Get the id currently authenticated user.
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @return string
     */
    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        $authId = $this->session->get($this->getName());
        if (is_null($authId)) {
            $token  = $this->jwtAuth->getToken();
            $authId = false !== $token ? $this->jwtAuth->getPayload($token)->get('sub') : null;
        }

        return $authId;
    }

}
