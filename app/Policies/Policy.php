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

    /**
     * @return bool
     */
    protected function isUser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    protected function isAdvertiser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADVERTISER]);
    }

    /**
     * @return bool
     */
    protected function isAdmin()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADMIN]);
    }

    /**
     * @return mixed
     */
    protected function hasAnyRole()
    {
        return $this->auth->user()->hasRoles(Role::getAllRoles());
    }
}
