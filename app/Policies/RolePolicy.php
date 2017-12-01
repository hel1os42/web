<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user)
    {
        return $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }
}
