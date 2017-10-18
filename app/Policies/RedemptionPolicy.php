<?php

namespace App\Policies;

use App\Models\NauModels\Redemption;

class RedemptionPolicy extends Policy
{

    /**
     * @return bool
     */
    public function getActivationCode()
    {
        return $this->isUser();
    }

    /**
     * @return bool
     */
    public function createFromOffer()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->isUser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->isUser();
    }

    /**
     * @return bool
     */
    public function redemption()
    {
        return $this->isUser();
    }

    /**
     * @param Redemption $redemption
     *
     * @return bool
     */
    public function show(Redemption $redemption)
    {
        return $redemption->offer->isOwner($this->auth->guard()->user());
    }

    /**
     * @return bool
     */
    public function showFromOffer()
    {
        return $this->isUser();
    }
}
