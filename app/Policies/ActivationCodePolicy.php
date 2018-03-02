<?php

namespace App\Policies;

use App\Models\ActivationCode;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ActivationCodePolicy extends Policy
{
    /**
     * @param Authenticatable $user
     *
     * @return bool
     */
    public function show(Authenticatable $user, ActivationCode $activationCode)
    {
        if ($user instanceof Operator) {
            $user = $user->place->user;
        }

        return $user instanceof User &&
            $activationCode->user->equals($user) ||
            $activationCode->offer->isOwner($user);
    }
}
