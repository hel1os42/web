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
    public function indexMy(User $user): bool
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
        return $user->hasAnyRole();
    }

    /**
     * @param User     $user
     * @param Transact $transaction
     *
     * @return bool
     */
    public function showMy(User $user, Transact $transaction): bool
    {
        return ($user->equals($transaction->source->owner) || $user->equals($transaction->destination->owner))
               && $user->hasAnyRole();
    }
}
