<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use App\Models\OfferLink;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferLinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list of offerLinks.
     *
     * @param User $user
     * @param Place $place
     *
     * @return bool
     */
    public function index(User $user, Place $place): bool
    {
        return $this->isAllowed($user, $place->user);
    }

    /**
     * Determine whether the user can view the offerLink.
     *
     * @param User $user
     * @param OfferLink $offerLink
     *
     * @return bool
     */
    public function show(User $user, OfferLink $offerLink)
    {
        return true;
    }

    /**
     * Determine whether the user can create offerLinks.
     *
     * @param User $user
     * @param Place $place
     *
     * @return bool
     */
    public function create(User $user, Place $place)
    {
        return $this->isAllowed($user, $place->user);
    }

    /**
     * Determine whether the user can update the offerLink.
     *
     * @param  User $user
     * @param  OfferLink $offerLink
     *
     * @return bool
     */
    public function update(User $user, OfferLink $offerLink)
    {
        return $this->isAllowed($user, $offerLink->place->user);
    }

    /**
     * Determine whether the user can delete the offerLink.
     *
     * @param  User $user
     * @param  OfferLink $offerLink
     *
     * @return bool
     */
    public function delete(User $user, OfferLink $offerLink)
    {
        return $this->isAllowed($user, $offerLink->place->user);
    }

    /**
     * @param User $authUser
     * @param User $affectedUser
     *
     * @return bool
     */
    private function isAllowed(User $authUser, User $affectedUser)
    {
        return $authUser->isAdmin()
            || (($authUser->isAgent() || $authUser->isChiefAdvertiser()) && $authUser->hasChild($affectedUser))
            || ($authUser->isAdvertiser() && $authUser->equals($affectedUser));
    }
}
