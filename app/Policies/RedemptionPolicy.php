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
        return $this->auth->user()->isUser();
    }

    /**
     * @return bool
     */
    public function createFromOffer()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->auth->user()->isUser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->auth->user()->isUser();
    }

    /**
     * @return bool
     */
    public function redemption()
    {
        return $this->auth->user()->isUser();
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
        return $this->auth->user()->isUser();
    }
}
