<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\User;
use OmniSynapse\CoreService\CoreService;

class UserObserver
{
    /**
     * @param User $user
     */
    public function created(User $user)
    {
        $coreService = app()->make(CoreService::class);
        dispatch($coreService->userCreated($user));
    }
}
