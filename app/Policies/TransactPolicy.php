<?php

namespace App\Policies;

use App\Models\User;

class TransactPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole();
    }
}
