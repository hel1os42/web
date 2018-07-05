<?php

namespace App\Policies\User;

use App\Models\Role;
use App\Models\User;
use App\Policies\Policy;
use App\Repositories\UserRepository;

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
        $excludedRoles = [Role::ROLE_ADMIN, Role::ROLE_AGENT];

        if ($user->isAgent()) {
            $excludedRoles[] = Role::ROLE_CHIEF_ADVERTISER;
        }

        $usersCount = app(UserRepository::class)
            ->skipCriteria()
            ->skipPresenter()
            ->scopeQuery(function($query) use ($childrenIds) {
                return $query->whereIn('id', $childrenIds);
            })
            ->whereHas('roles', function($query) use ($excludedRoles) {
                $query->whereNotIn('name', $excludedRoles);
            })
            ->all(['id'])
            ->count();

        return !$editableUser->hasRoles([Role::ROLE_ADMIN, Role::ROLE_ADVERTISER, Role::ROLE_USER])
            && $user->hasRoles([Role::ROLE_ADMIN, Role::ROLE_AGENT])
            && count($childrenIds) === $usersCount;
    }
}
