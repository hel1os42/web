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
     * @param array $roleNames
     *
     * @return bool
     */
    public function hasRoles(array $roleNames): bool
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
    public function isAdmin(): bool
    {
        return $this->hasRoles([Role::ROLE_ADMIN]);
    }

    /**
     * @return bool
     */
    public function isAgent(): bool
    {
        return $this->hasRoles([Role::ROLE_AGENT]);
    }

    /**
     * @return bool
     */
    public function isChiefAdvertiser(): bool
    {
        return $this->hasRoles([Role::ROLE_CHIEF_ADVERTISER]);
    }

    /**
     * @return bool
     */
    public function isAdvertiser(): bool
    {
        return $this->hasRoles([Role::ROLE_ADVERTISER]);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return mixed
     */
    public function hasAnyRole()
    {
        return $this->hasRoles(Role::getAllRoles());
    }
}
