<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * @return bool
     */
    public function index(User $currentUser)
    {
        return $currentUser->isAdmin()
               || $this->auth->user()->isChiefAdvertiser() || $this->auth->user()->isAgent();
    }

    /**
     * @param User $user
     *
     * @return bool|mixed
     */
    public function show(User $currentUser, User $user)
    {
        if ($this->auth->user()->isAdmin()
            || ($this->auth->user()->hasAnyRole() && $user->equals($this->auth->user()))) {
            return true;
        }

        return ($this->auth->user()->isChiefAdvertiser() || $this->auth->user()->isAgent())
               && $user->hasParent($this->auth->user());
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->isAdmin()
               || ($currentUser->isAgent() && $currentUser->hasChild($user))
               || ($currentUser->hasAnyRole() && $user->equals($currentUser));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function referrals(User $currentUser, User $user)
    {
        return $this->auth->user()->isAdmin()
               || ($this->auth->user()->hasAnyRole() && $user->equals($this->auth->user()));
    }

    /**
     * @return bool
     */
    public function pictureStore()
    {
        return $this->auth->user()->isUser();
    }

    /**
     * @return bool
     */
    public function pictureShow()
    {
        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function setChildren(User $currentUser, User $user)
    {
        return ($this->auth->user()->isAdmin()
                || ($this->auth->user()->isAgent() && $this->auth->user()->hasChild($user)))
               && ($user->isAgent() || $user->isChiefAdvertiser());
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function setParents(User $currentUser, User $user)
    {
        return ($this->auth->user()->isAdmin()
                || ($this->auth->user()->isAgent() && $this->auth->user()->hasChild($user)))
               && ($user->isChiefAdvertiser() || $user->isAdvertiser());
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateRoles(User $currentUser, User $user)
    {
        return $currentUser->isAdmin()
               || ($this->auth->user()->isAgent() && $this->auth->user()->hasChild($user));
    }
}
