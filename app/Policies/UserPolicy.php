<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function show(User $user, User $anotherUser)
    {
        if ($user->hasRoles([Role::ROLE_ADMIN])
            || ($user->hasAnyRole() && $anotherUser->equals($user))
        ) {
            return true;
        }

        return ($user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT]))
               && $anotherUser->hasParent($user);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function update(User $user, User $anotherUser)
    {
        return $user->hasRoles([Role::ROLE_ADMIN])
               || ($user->isAgent() && $user->hasChild($anotherUser))
               || ($user->hasAnyRole() && $anotherUser->equals($user));
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function referrals(User $user, User $anotherUser)
    {
        return $user->hasRoles([Role::ROLE_ADMIN])
               || ($user->hasAnyRole() && $anotherUser->equals($user));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function pictureStore(User $user)
    {
        return $user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function updateChildren(User $user, User $anotherUser)
    {
        return ($user->hasRoles([Role::ROLE_ADMIN])
                || ($user->isAgent() && $user->hasChild($anotherUser)))
               && ($anotherUser->hasRoles([Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER]));
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function updateParents(User $user, User $anotherUser)
    {
        return ($user->hasRoles([Role::ROLE_ADMIN])
                || ($user->isAgent() && $user->hasChild($anotherUser)))
               && ($anotherUser->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_ADVERTISER]));
    }

    /**
     * @param User      $user
     * @param User|null $anotherUser
     *
     * @return bool
     */
    public function updateRoles(User $user, User $anotherUser)
    {
        return $user->hasRoles([Role::ROLE_ADMIN])
               || ($user->isAgent() && $user->hasChild($anotherUser));
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function impersonate(User $user, User $anotherUser)
    {
        return ($user->hasRoles([Role::ROLE_ADMIN]) || $user->hasChild($anotherUser))
               && $user->isImpersonated() === false;
    }
}
