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
        if (is_null(auth()->user())) {
            $user->roles()->attach([
                Role::findByName(Role::ROLE_USER)->getId(),
                Role::findByName(Role::ROLE_ADVERTISER)->getId()
            ]);
        } elseif (auth()->user()->isAgent()) {
            $user->roles()->attach([
                Role::findByName(Role::ROLE_ADVERTISER)->getId()
            ]);
            $user->parents()->attach([auth()->user()->id]);
        }
    }
}
