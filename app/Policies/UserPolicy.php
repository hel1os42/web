<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * @return bool
     */
    public function adminUserList()
    {
        return $this->isAdmin();
    }

    /**
     * @return bool
     */
    public function adminSetChildren()
    {
        return $this->isAdmin();
    }

    /**
     * @return bool
     */
    public function adminSetParents()
    {
        return $this->isAdmin();
    }

    /**
     * @return bool
     */
    public function adminUpdateRoles()
    {
        return $this->isAdmin();
    }

    /**
     * @return bool
     */
    public function profileIndex()
    {
        return $this->isUser();
    }

    /**
     * @param User $user
     *
     * @return bool|mixed
     */
    public function profileShow(User $user)
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
    public function profileUpdate(User $user)
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
     * @param User $user
     *
     * @return bool
     */
    public function profileReferrals(User $user)
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
