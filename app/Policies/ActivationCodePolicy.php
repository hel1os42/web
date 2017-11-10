<?php

namespace App\Policies;

use App\Models\Role;

class ActivationCodePolicy extends Policy
{
    /**
     * @return bool
     */
    public function show()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_USER]);
    }
}
