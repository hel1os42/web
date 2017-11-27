<?php

namespace App\Policies;

use App\Models\Place;

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
    public function showMy(Place $place)
    {
        return $this->user->isAdvertiser() && $this->user->equal($place->user);
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
     * @param Place $place
     *
     * @return bool
     */
    public function pictureStore(Place $place)
    {
        return $this->user->isAdvertiser() && $this->user->equal($place->user);
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function update(Place $place)
    {
        return $this->user->isAdvertiser() && $this->user->equal($place->user);
    }
}
