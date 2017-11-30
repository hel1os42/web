<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user)
    {
        if ($this->user->hasRoles([Role::ROLE_ADMIN])
            || ($this->user->hasAnyRole() && $user->equals($this->user))) {
            return true;
        }

        return ($this->user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]))
               && $user->hasParent($this->user);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $user)
    {
        return $this->user->hasRoles([Role::ROLE_ADMIN])
               || ($this->user->isAgent() && $this->user->hasChild($user))
               || ($this->user->hasAnyRole() && $user->equals($this->user));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function referrals(User $user)
    {
        return $this->user->hasRoles([Role::ROLE_ADMIN])
               || ($this->user->hasAnyRole() && $user->equals($this->user));
    }

    /**
     * @return bool
     */
    public function pictureStore()
    {
        return $this->user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateChildren(User $user)
    {
        return ($this->user->hasRoles([Role::ROLE_ADMIN])
                || ($this->user->isAgent() && $this->user->hasChild($user)))
               && ($user->hasRoles([Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER]));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateParents(User $user)
    {
        return ($this->user->hasRoles([Role::ROLE_ADMIN])
                || ($this->user->isAgent() && $this->user->hasChild($user)))
               && ($user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_ADVERTISER]));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateRoles(User $user)
    {
        return $this->user->hasRoles([Role::ROLE_ADMIN])
               || ($this->user->isAgent() && $this->user->hasChild($user));
    }
}
