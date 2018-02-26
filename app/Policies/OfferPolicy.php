<?php

namespace App\Policies;

use App\Models\NauModels\Offer;
use App\Models\Operator;
use App\Models\Role;
use App\Models\User;

class OfferPolicy extends Policy
{

    /**
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->hasAnyRole();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function show(User $user): bool
    {
        return $user->hasAnyRole() || $user instanceof Operator;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function indexMy(User $user): bool
    {
        return $user->isAdvertiser();
    }

    /**
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function showMy(User $user, Offer $offer): bool
    {
        if ($user->hasRoles([Role::ROLE_ADMIN])) {
            return true;
        }

        if ($user->isAdvertiser() && $offer->isOwner($user)) {
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
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function destroy(User $user, Offer $offer): bool
    {
        return ($user->isAdvertiser() && $offer->isOwner($user))
               || $user->isAdmin()
               || ($user->isAgent() && $user->hasChild($offer->getOwner()));
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->place !== null && $user->isAdvertiser();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function update(User $user, Offer $offer): bool
    {
        return ($user->isAdvertiser() && $offer->isOwner($user))
               || $user->isAdmin()
               || ($user->isAgent() && $user->hasChild($offer->getOwner()));
    }

    /**
     * @param User  $user
     * @param Offer $offer
     *
     * @return bool
     */
    public function pictureStore(User $user, Offer $offer): bool
    {
        return ($user->isAdvertiser() && $offer->isOwner($user))
               || $user->isAdmin()
               || ($user->isAgent() && $user->hasChild($offer->getOwner()));
    }

    /**
     * @param User $user
     * @param User $owner
     *
     * @return bool
     */
    public function pictureStoreByOfferData(User $user, User $owner): bool
    {
        return ($user->isAdvertiser() && $user->equals($owner))
               || $user->isAdmin()
               || ($user->isAgent() && $user->hasChild($owner));
    }
}
