<?php

namespace App\Policies;

use App\Models\NauModels\Account;
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
     * @param User    $user
     * @param Account $sourceAccount
     *
     * @return bool
     */
    public function create(User $user, Account $sourceAccount): bool
    {
        return $user->hasAnyRole()
               && $user->equals($sourceAccount->owner);
    }

    /**
     * @param User    $user
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     *
     * @return bool
     */
    public function createNoFee(User $user, Account $sourceAccount, Account $destinationAccount): bool
    {
        return $user->isAdmin()
            || ($user->equals($sourceAccount->owner)
                && $user->isAgent()
                && $user->hasChild($destinationAccount->owner));
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
