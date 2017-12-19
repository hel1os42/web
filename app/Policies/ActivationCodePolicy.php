<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class ActivationCodePolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user)
    {
        return $user->hasRoles([Role::ROLE_USER]);
    }
}
