<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\AuthManager;

class Policy
{
    use HandlesAuthorization;

    protected $user;

    /**
     * Policy constructor.
     *
     * @param AuthManager $authManager
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthManager $authManager)
    {
        $this->user = $this->getUser($authManager);
    }

    /**
     * @param AuthManager $authManager
     *
     * @return User
     * @throws \InvalidArgumentException
     */
    private function getUser(AuthManager $authManager): User
    {
        return $authManager->guard()->user();
    }
}
