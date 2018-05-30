<?php

namespace App\Services\Auth\Guards;

use App\Repositories\IdentityRepository;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class IdentityGuard implements Guard
{
    use GuardHelpers;

    /**
     * @var IdentityRepository
     */
    protected $identityRepository;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    public function __construct(UserProvider $userProvider, IdentityRepository $identityRepository)
    {
        $this->userProvider       = $userProvider;
        $this->identityRepository = $identityRepository;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return array_has($credentials, [
            'identity_provider',
            'identity_access_token',
        ]);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        return $this->user = $this->provider->retrieveByCredentials(request()->all());
    }
}
