<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class TagPolicy extends Policy
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

    /**
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }
}
