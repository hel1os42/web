<?php

namespace app\Observers;

use App\Models\Role;
use App\Models\User;

class UserObserver
{
    /**
     * @param User $user
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function created(User $user)
    {
        $user->roles()->attach([
            Role::findByName(Role::ROLE_USER)->getId(),
            Role::findByName(Role::ROLE_ADVERTISER)->getId()
        ]);

        if ($user->referrer instanceof User) {
            $user->referrer->enrollReferralPoints();
        }
    }
}
