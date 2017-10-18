<?php

namespace App\Policies;

class PlacePolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function showOwnerPlace()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function showPlaceOffers()
    {
        return $this->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function showOwnerPlaceOffers()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function update()
    {
        return $this->isAdvertiser();
    }
}
