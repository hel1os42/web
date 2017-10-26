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
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->isAdvertiser();
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

        if ($this->isAdvertiser() && $offer->isOwner($user)) {
            return true;
        }

        if ($user->hasRoles([Role::ROLE_CHIEF_ADVERTISER, Role::ROLE_AGENT])) {
            $owner = $offer->getOwner();
            if ($owner !== null) {
                return $owner->hasParent($user);
            }
        }

        return false;

    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    public function destroy(User $user, Offer $offer)
    {
        if ($this->isAdvertiser() && $offer->isOwner($user)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function userIndex()
    {
        return $this->isUser();
    }

    /**
     * @return bool
     */
    public function userShow()
    {
        return $this->isUser();
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    public function pictureStore(Offer $offer)
    {
        return $this->isAdvertiser() && $offer->isOwner($this->auth->user());
    }

    /**
     * @return bool
     */
    private function isUser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_USER]);
    }

    /**
     * @return bool
     */
    private function isAdvertiser()
    {
        return $this->auth->user()->hasRoles([Role::ROLE_ADVERTISER]);
    }
}
