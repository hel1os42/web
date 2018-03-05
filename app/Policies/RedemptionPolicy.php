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
            $user = $user->place->user ?? null;
        }

        return $user->hasRoles([Role::ROLE_USER, Role::ROLE_ADVERTISER, Role::ROLE_ADMIN]);
    }

    /**
     * @param       $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function confirm(Authenticatable $user, Offer $offer)
    {
        if ($user instanceof Operator) {
            $user = $user->place->user ?? null;
        }

        return $user instanceof User && $user->hasRoles([Role::ROLE_ADVERTISER]) && $offer->isOwner($user);
    }

    /**
     * @param User       $user
     * @param Redemption $redemption
     *
     * @return bool
     */
    public function show(User $user, Redemption $redemption)
    {
        if ($user instanceof Operator) {
            $user = $user->place->user ?? null;
        }

        return $redemption->offer->isOwner($user);
    }
}
