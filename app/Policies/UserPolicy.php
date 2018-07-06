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
     * @param User  $user
     * @param array $userData
     *
     * @return bool
     */
    public function create(User $user, $userData = []): bool
    {
        return !isset($userData['approved']) && $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function show(User $user, User $anotherUser)
    {
        if ($user->isAdmin()
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
    public function referrals(User $user, User $anotherUser)
    {
        return $user->isAdmin()
               || ($user->hasAnyRole() && $anotherUser->equals($user));
    }

    /**
     * @param User $user
     * @param User $editableUser
     *
     * @return bool
     */
    public function pictureStore(User $user, User $editableUser)
    {
        return ($user->hasAnyRole() && $editableUser->equals($user))
               || (($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($editableUser))
               || $user->isAdmin();
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function impersonate(User $user, User $anotherUser)
    {
        return ($user->isAdmin() || $user->hasChild($anotherUser))
               && $user->isImpersonated() === false;
    }
}
