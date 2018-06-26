<?php

namespace App\Policies\User;

use App\Models\Role;
use App\Models\User;
use App\Policies\Policy;

/**
 * Class ChildPolicy
 * @package App\Policies\User
 */
class ChildPolicy extends Policy
{
    /**
     * @param User $user
     * @param User $editableUser
     *
     * @return bool
     */
    public function index(User $user, User $editableUser): bool
    {
        return !$editableUser->hasRoles([Role::ROLE_ADMIN, Role::ROLE_ADVERTISER, Role::ROLE_USER])
            && ($user->isAdmin() || $user->hasChild($editableUser));
    }

    /**
     * @param User  $user
     * @param User  $editableUser
     * @param array $childrenIds
     *
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(User $user, User $editableUser, array $childrenIds): bool
    {
        foreach ($childrenIds as $childId) {
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
}
