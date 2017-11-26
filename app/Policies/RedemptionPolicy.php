<?php

namespace App\Policies;

use App\Models\NauModels\Redemption;
use App\Models\Role;

class RedemptionPolicy extends Policy
{

    /**
     * @return bool
     */
    public function code($currentUser)
    {
        return $currentUser->hasRole([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function createFromOffer()
    {
        return $this->user->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function redeem()
    {
        return $this->user->hasRoles([Role::ROLE_USER]);
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
        return $this->user->hasRoles([Role::ROLE_USER]);
    }
}
