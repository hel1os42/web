<?php

namespace App\Services\User;

use App\Models\User;

interface ConfirmationService
{
    public function make(User $user);

    public function confirm(string $token): bool;

    public function disapprove(User $user);
}
