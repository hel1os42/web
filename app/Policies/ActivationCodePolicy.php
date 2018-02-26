<?php

namespace App\Policies;

use App\Models\ActivationCode;
use App\Models\User;

class ActivationCodePolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user, ActivationCode $activationCode)
    {
        return $activationCode->user->equals($user) ||
            $activationCode->offer->isOwner($user);
    }
}
