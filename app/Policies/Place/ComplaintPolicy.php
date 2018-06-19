<?php

namespace App\Policies\Place;

use App\Models\Place;
use App\Models\User;
use App\Policies\Policy;

class ComplaintPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function create(User $user, Place $place): bool
    {
        return $user->isUser();
    }
}
