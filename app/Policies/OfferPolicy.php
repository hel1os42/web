<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\Role;
use App\Models\User;

class OfferPolicy extends Policy
{

    /**
     * @return bool
     */
    public function index()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->auth->user()->hasAnyRole();
    }

    /**
     * @return bool
     */
    public function indexMy()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function showMy(User $user, Offer $offer)
    {
        if ($this->auth->user()->hasRoles([Role::ROLE_ADMIN])) {
            return true;
        }

        if ($this->auth->user()->isAdvertiser() && $offer->isOwner($user)) {
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
     * @return bool
     */
    public function create()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @return bool
     */
    public function update(): bool
    {
        return $this->auth->user()->isAdvertiser();
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     */
    public function pictureStore(Offer $offer)
    {
        return $this->auth->user()->isAdvertiser() && $offer->isOwner($this->auth->user());
    }
}
