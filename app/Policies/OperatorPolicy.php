<?php

namespace App\Policies;

use App\Models\Operator;
use App\Models\User;

class OperatorPolicy extends Policy
{

    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->isAdvertiser();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->isAdvertiser();
    }

    /**
     * @param User  $user
     * @param Operator $operator
     *
     * @return bool
     */
    public function destroy(User $user, Operator $operator): bool
    {
        return $user->isAdvertiser() &&
            $user->id === $operator->place()->firstOrFail()->user_id;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdvertiser();
    }

    /**
     * @param User $user
     * @param Operator operator
     *
     * @return bool
     */
    public function update(User $user, Operator $operator): bool
    {
        return $user->isAdvertiser() &&
            $user->id === $operator->place()->firstOrFail()->user_id;
    }
}
