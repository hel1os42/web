<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\Operator;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class RedemptionPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user)
    {
        if ($user instanceof Operator) {
            $user = $user->isActive() && $user->place->user ? $user->place->user : null;
        }

        return $user->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @param $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function confirm(Authenticatable $user, Offer $offer)
    {
        if ($user instanceof Operator) {
            $user = $user->isActive() && $user->place->user ? $user->place->user : null;
        }

        return $user instanceof User && $user->hasRoles([Role::ROLE_ADVERTISER]) && $offer->isOwner($user);
    }

    /**
     * @param Authenticatable $user
     * @param Redemption      $redemption
     *
     * @return bool
     */
    public function show(Authenticatable $user, Redemption $redemption)
    {
        if ($user instanceof Operator) {
            $user = $user->isActive() && $user->place->user ? $user->place->user : null;
        }

        return $user instanceof User && $redemption->offer->isOwner($user);
    }
}
