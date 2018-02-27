<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy extends Policy
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
    public function show(User $user): bool
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
        return $user->isAdmin();
    }

    /**
     * @param User     $user
     * @param Category $category
     *
     * @return bool
     */
    public function update(User $user, Category $category): bool
    {
        return $category->parent_id !== null && $user->isAdmin();
    }

    /**
     * @param User     $user
     *
     * @return bool
     */
    public function pictureStore(User $user): bool
    {
        return $user->isAdmin();
    }
}
