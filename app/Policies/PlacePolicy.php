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
     * @return bool
     */
    public function showMy(User $user)
    {
        return $user->isAdvertiser();
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
    public function createMy(User $user)
    {
        return $user->isAdvertiser();
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
               || ($user->isAdvertiser() && $user->equals($place->user));
    }
}
