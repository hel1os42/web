<?php

namespace App\Services;

use App\Models\IdentityProvider;
use App\Models\User;
use Laravel\Socialite\Two\User as UserIdentity;

interface UserService
{

    /**
     *  Create new or find registered user
     *
     * @param array $attributes
     *
     * @return User
     */
    public function make(array $attributes): User;

    /**
     * @param User $issuer
     */
    public function setIssuer(User $user);

    /**
     * @param array $attributes
     * @param UserIdentity $userIdentity
     * @param IdentityProvider $identityProvider
     *
     * @return User
     */
    public function register(array $attributes, UserIdentity $userIdentity, IdentityProvider $identityProvider): User;

}