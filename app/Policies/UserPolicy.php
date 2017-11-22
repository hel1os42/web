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
        return $this->auth->user()->hasRoles([Role::ROLE_ADMIN, Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]);
    }

    /**
     * @param User $currentUser
     * @param User $user
     *
     * @return bool
     */
    public function show(User $currentUser, User $user)
    {
        if ($currentUser->hasRoles([Role::ROLE_ADMIN])
            || ($currentUser->hasAnyRole() && $user->equals($currentUser))) {
            return true;
        }

        return ($currentUser->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]))
               && $user->hasParent($currentUser);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->hasRoles([Role::ROLE_ADMIN])
               || ($currentUser->isAgent() && $currentUser->hasChild($user))
               || ($currentUser->hasAnyRole() && $user->equals($currentUser));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function referrals(User $currentUser, User $user)
    {
        return $currentUser->hasRoles([Role::ROLE_ADMIN])
               || ($currentUser->hasAnyRole() && $user->equals($currentUser));
    }

    /**
     * @return bool
     */
    public function pictureStore()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function pictureShow()
    {
        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateChildren(User $currentUser, User $user)
    {
        return ($currentUser->hasRoles([Role::ROLE_ADMIN])
                || ($currentUser->isAgent() && $currentUser->hasChild($user)))
               && ($user->hasRoles([Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER]));
    }

    /**
     * @param User $currentUser
     * @param User $user
     *
     * @return bool
     */
    public function updateParents(User $currentUser, User $user)
    {
        return ($currentUser->hasRoles([Role::ROLE_ADMIN])
                || ($currentUser->isAgent() && $currentUser->hasChild($user)))
               && ($user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_ADVERTISER]));
    }

    /**
     * @param User $currentUser
     * @param User $user
     *
     * @return bool
     */
    public function updateRoles(User $currentUser, User $user)
    {
        return $currentUser->hasRoles([Role::ROLE_ADMIN])
               || ($currentUser->isAgent() && $currentUser->hasChild($user));
    }
}
