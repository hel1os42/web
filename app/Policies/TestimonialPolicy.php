<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Place;
use App\Models\User;

class TestimonialPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isUser();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->isAdmin();
    }
}
