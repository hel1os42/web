<?php

namespace App\Models\User;

use App\Models\Role;

/**
 * Trait RoleTrait
 *
 * @package App\Models\User
 */
trait RoleTrait
{
    /**
     * @return bool
     */
    public function isAdvertiser()
    {
        return $this->hasRoles([Role::ROLE_ADVERTISER]);
    }

    /**
     * @param array $roleNames
     *
     * @return bool
     */
    public function hasRoles(array $roleNames)
    {
        foreach ($this->roles as $userRole) {
            if (in_array($userRole->name, $roleNames)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAgent()
    {
        return $this->hasRoles([Role::ROLE_AGENT]);
    }

    /**
     * @return mixed
     */
    public function hasAnyRole()
    {
        return $this->hasRoles(Role::getAllRoles());
    }
}
