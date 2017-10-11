<?php

namespace App\Policies;

use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @return bool
     */
    public function adminUserList()
    {
        return auth()->user()->hasRoles([Role::ROLE_ADMIN]) ? true : false;
    }
}
