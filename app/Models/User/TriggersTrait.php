<?php

namespace App\Models\User;

/**
 * Trait TriggersTrait
 *
 * @package App\Models\User
 */
trait TriggersTrait
{

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
    public function isUser()
    {
        return $this->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function isAdvertiser()
    {
        return $this->hasRoles([Role::ROLE_ADVERTISER]);
    }

    /**
     * @return bool
     */
    public function isAgent()
    {
        return $this->hasRoles([Role::ROLE_AGENT]);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRoles([Role::ROLE_ADMIN]);
    }

    /**
     * @return mixed
     */
    public function hasAnyRole()
    {
        return $this->hasRoles(Role::getAllRoles());
    }

    /**
     * @param User $parent
     *
     * @return mixed
     */
    public function hasParent(User $parent)
    {
        return $this->parents->contains($parent->getId());
    }

    /**
     * @param User $child
     *
     * @return bool
     */
    public function hasChild(User $child)
    {
        return $this->children->contains($child->getId());
    }
}
