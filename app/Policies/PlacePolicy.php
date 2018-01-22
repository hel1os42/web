<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\Role;
use App\Models\User;

class PlacePolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function show(User $user)
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function showOffers(User $user)
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user, User $forUser)
    {
        return $user->isAdmin()
               || (($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($forUser))
               || ($user->isAdvertiser() && $user->equals($forUser));
    }

    /**
     * @param User  $user
     * @param Place $place
     *
     * @return bool
     */
    public function pictureStore(User $user, Place $place)
    {
        return $user->hasRoles([Role::ROLE_ADMIN])
               || (($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($place->user))
               || ($user->isAdvertiser() && $user->equals($place->user));
    }

    /**
     * @param User  $user
     * @param Place $place
     *
     * @return bool
     */
    public function update(User $user, Place $place)
    {
        return $user->hasRoles([Role::ROLE_ADMIN])
               || (($user->isAgent() || $user->isChiefAdvertiser()) && $user->hasChild($place->user))
               || ($user->isAdvertiser() && $user->equals($place->user));
    }
}
