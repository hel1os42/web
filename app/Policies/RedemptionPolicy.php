<?php

namespace App\Policies;

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
    public function confirm()
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
}
