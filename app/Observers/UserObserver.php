<?php

namespace app\Observers;

use App\Models\Role;
use App\Models\User;
use App\Services\User\ConfirmationService;

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

        $this->confirmEmail($user);
    }

    public function updated(User $user)
    {
        if ($user->isDirty('email')) {
            app(ConfirmationService::class)->disapprove($user);

            $this->confirmEmail($user);
        }
    }

    private function confirmEmail(User $user)
    {
        // turn on later
        return $user;

        //if (null !== $user->email) {
        //    app(ConfirmationService::class)->make($user);
        //}
    }
}
