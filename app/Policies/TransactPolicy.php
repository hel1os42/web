<?php

namespace App\Policies;

use App\Models\NauModels\Transact;
use App\Models\User;

class TransactPolicy extends Policy
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user, User $byUser): bool
    {
        return $user->hasAnyRole()
               && ($user->equals($byUser)
                   || ($user->isAgent() && $user->hasChild($byUser))
                   || $user->isAdmin());
    }

    /**
     * @param User $user
     * @param User $sourceUser
     *
     * @return bool
     */
    public function create(User $user, User $sourceUser): bool
    {
        return $user->hasAnyRole()
               && ($user->equals($sourceUser)
                   || $user->isAdmin());
    }

    /**
     * @param User     $user
     * @param Transact $transaction
     *
     * @return bool
     */
    public function show(User $user, Transact $transaction): bool
    {
        $sourceUser      = $transaction->source->owner;
        $destinationUser = $transaction->destination->owner;

        return $user->hasAnyRole()
               && ($user->equals($sourceUser)
                   || $user->equals($destinationUser)
                   || ($user->isAgent() && ($user->hasChild($sourceUser) || $user->hasChild($destinationUser)))
                   || $user->isAdmin());
    }
}
