<?php

namespace App\Policies;

use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\AuthManager;

class Policy
{
    use HandlesAuthorization;

    protected $auth;

    public function __construct(AuthManager $authManager)
    {
        $this->auth = $authManager->guard();
    }
}
