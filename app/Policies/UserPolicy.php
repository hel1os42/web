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
     * @param User  $user
     * @param User  $editableUser
     * @param array $userData
     *
     * @return bool
     */
    public function update(User $user, User $editableUser, array $userData = [])
    {
        return ($user->hasAnyRole() && $editableUser->equals($user) && !isset($userData['approved']))
               || (($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($editableUser))
               || $user->isAdmin();
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $roleIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function updateRoles(User $user, User $editableUser, array $roleIds): bool
    {
        if (count($roleIds) > 1
            && count(array_diff([
                Role::findByName(Role::ROLE_ADVERTISER)->getId(),
                Role::findByName(Role::ROLE_USER)->getId()
            ], $roleIds)) > 0) {
            return false;
        }

        /**
         * @var Role $role
         */
        $role = (new Role)->findOrFail($roleIds[0]);
        if ($user->isAgent()
            && ($role->equalsByName(Role::ROLE_ADMIN)
                || $role->equalsByName(Role::ROLE_AGENT))) {
            return false;
        }


        return $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $parentIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateParents(User $user, User $editableUser, array $parentIds)
    {
        foreach ($parentIds as $parentId) {
            /**
             * @var User $parentUser
             */
            $parentUser = (new User)->findOrFail($parentId);
            if (!$parentUser->equals($user) && !$parentUser->hasParent($user) && $user->isAgent()) {
                return false;
            }
            if (!$parentUser->hasRoles([Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER])) {
                return false;
            }
        }

        return !$editableUser->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])
               && $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT, Role::ROLE_CHIEF_ADVERTISER]);
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $childIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateChildren(User $user, User $editableUser, array $childIds)
    {
        foreach ($childIds as $childId) {
            /**
             * @var User $child
             */
            $child = (new User)->findOrFail($childId);
            if ($child->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])) {
                return false;
            }
            if ($child->hasRoles([Role::ROLE_CHIEF_ADVERTISER]) && $user->isAgent()) {
                return false;
            }
        }

        return !$editableUser->hasRoles([Role::ROLE_ADMIN, Role::ROLE_ADVERTISER, Role::ROLE_USER])
               && $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT]);
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
