<?php

namespace App\Policies;

use App\Models\User;

class CategoryPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->hasAnyRole();
    }
}
