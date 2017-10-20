<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index()
    {
        return $this->isAdmin();
    }

    /**
     * @param User $user
     *
     * @return bool|mixed
     */
    public function show(User $user)
    {
        if ($this->auth->user()->hasRoles([Role::ROLE_ADMIN])) {
            return true;
        }

        if ($this->isUser() && $user->equals($this->auth->user())) {
            return true;
        }

        if ($this->auth->user()->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT])) {
            return $user->hasParent($this->auth->user());
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $user)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasAnyRole() && $user->equals($this->auth->user())) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function referrals(User $user)
    {
        if ($this->auth->user()->hasRoles([Role::ROLE_ADMIN])) {
            return true;
        }

        if ($this->isUser() && $user->equals($this->auth->user())) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function pictureStore()
    {
        return $this->isUser();
    }

    /**
     * @return bool
     */
    public function pictureShow()
    {
        return true;
    }
}
