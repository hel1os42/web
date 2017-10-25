<?php

namespace App\Policies;

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
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function showOwnerPlace()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function showPlaceOffers()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function showOwnerPlaceOffers()
    {
        return $this->auth->user()->isAdvertiser();
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
     * @return bool
     */
    public function update()
    {
        return $this->auth->user()->isAdvertiser();
    }
}
