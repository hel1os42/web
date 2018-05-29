<?php

namespace App\Policies;

use App\Models\User;

class SettingsPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
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