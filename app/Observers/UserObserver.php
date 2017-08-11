<?php

namespace App\Observers;

use App\Models\User;
use OmniSynapse\CoreService\CoreService;

class UserObserver
{
    /**
     * @param User $user
     */
    public function creating(User $user)
    {
        $coreService = app()->make(CoreService::class);
        $coreService->userCreated($user)
            ->handle();
    }
}
