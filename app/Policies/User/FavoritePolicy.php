<?php

namespace App\Policies;

use App\Models\User;

class FavoritePolicy extends Policy
{
    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function index(User $user, User $anotherUser)
    {
        return $user->isAdmin()
               || $anotherUser->equals($user);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function create(User $user, User $anotherUser)
    {
        return $user->isAdmin()
               || $anotherUser->equals($user);
    }

    /**
     * @param User $user
     * @param User $anotherUser
     *
     * @return bool
     */
    public function destroy(User $user, User $anotherUser)
    {
        return $user->isAdmin()
               || $anotherUser->equals($user);
    }
}
