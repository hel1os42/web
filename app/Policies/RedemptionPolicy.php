<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\Operator;
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
     * @param $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function confirm($user, Offer $offer)
    {
        if ($user instanceof Operator) {
            $offer->getOwner()->id === $user->place()->user_id ? true : false;
        }

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
