<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;

class PlacePolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->user->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->user->hasAnyRole();
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function showMy()
    {
        return $this->user->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function showOffers()
    {
        return $this->user->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->user->isAdvertiser();
    }

    /**
     * @param User  $user
     * @param Place $place
     *
     * @return bool
     */
    public function pictureStore(User $user, Place $place)
    {
        return $user->isAdvertiser() && $user->equals($place->user);
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function update(Place $place)
    {
        return $this->user->isAdvertiser() && $this->user->equals($place->user);
    }
}
