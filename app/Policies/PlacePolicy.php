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
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function showMy(Place $place)
    {
        return $this->auth->user()->isAdvertiser() && $this->auth->user()->equal($place->user);
    }

    /**
     * @return bool
     */
    public function showOffers()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function pictureStore(Place $place)
    {
        return $this->auth->user()->isAdvertiser() && $this->auth->user()->equal($place->user);
    }

    /**
     * @param Place $place
     *
     * @return bool
     */
    public function update(Place $place)
    {
        return $this->auth->user()->isAdvertiser() && $this->auth->user()->equal($place->user);
    }
}
