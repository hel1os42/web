<?php

namespace App\Policies;

use App\Models\Role;

class RolePolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }
}
