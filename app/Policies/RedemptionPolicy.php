<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\Role;
use App\Models\User;

class RedemptionPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        return $user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function confirm(User $user, Offer $offer)
    {
        return $user->hasRoles([Role::ROLE_ADVERTISER]) && $offer->isOwner($user);
    }

    /**
     * @param User       $user
     * @param Redemption $redemption
     *
     * @return bool
     */
    public function show(User $user, Redemption $redemption)
    {
        return $redemption->offer->isOwner($user);
    }
}
