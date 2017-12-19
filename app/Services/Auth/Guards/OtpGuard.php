<?php

namespace App\Services\Auth\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Class OtpGuard
 * NS: App\Services\Auth
 */
class OtpGuard implements Guard
{
    use GuardHelpers;

    private $credentials = [];

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     *
     * @return void
     */
    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->provider->retrieveByCredentials($this->credentials);
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
        if (!isset($credentials['phone'], $credentials['code'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

        return null !== $user && $this->provider->validateCredentials($user, $credentials);
    }
}
