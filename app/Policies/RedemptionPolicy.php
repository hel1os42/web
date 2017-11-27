<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\Role;

class RedemptionPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    public function confirm(Offer $offer)
    {
        return $this->user->hasRoles([Role::ROLE_ADVERTISER]) && $offer->isOwner($this->user);
    }

    /**
     * @param Redemption $redemption
     *
     * @return bool
     */
    public function show(Redemption $redemption)
    {
        return $redemption->offer->isOwner($this->user);
    }
}
