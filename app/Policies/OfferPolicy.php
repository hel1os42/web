<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\AuthManager;

class OfferPolicy
{
    use HandlesAuthorization;

    private $auth;

    public function __construct(AuthManager $authManager)
    {
        $this->auth = $authManager->guard();
    }

    /**
     * @return bool
     */
    public function index()
    {
        return $this->onlyAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->onlyAdvertiser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->onlyAdvertiser();
    }

    /**
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function show(User $user, Offer $offer)
    {
        if ($user->hasRoles([Role::ROLE_ADMIN])) {
            return true;
        }

        if ($user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT])) {
            return false; //todo check if agent or main advert can view offer
        }

        return $this->onlyAdvertiser() ? $offer->isOwner($this->auth->user()) : false;
    }

    /**
     * @return bool
     */
    public function userIndex()
    {
        return $this->onlyUser();
    }

    /**
     * @return bool
     */
    public function userShow()
    {
        return $this->onlyUser();
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    public function pictureStore(Offer $offer)
    {
        return $this->onlyAdvertiser() ? $offer->isOwner($this->auth->user()) : false;
    }

    /**
     * @return bool
     */
    private function onlyUser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_USER]) ? true : false;
    }

    /**
     * @return bool
     */
    private function onlyAdvertiser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADVERTISER]) ? true : false;
    }
}
