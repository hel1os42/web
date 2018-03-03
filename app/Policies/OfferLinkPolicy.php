<?php

namespace App\Policies;

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
     *
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->isAdvertiser();
    }

    /**
     * Determine whether the user can view the offerLink.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\OfferLink $offerLink
     * @return mixed
     */
    public function view(User $user, OfferLink $offerLink)
    {
        return $user->equals($offerLink->user);
    }

    /**
     * Determine whether the user can create offerLinks.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdvertiser();
    }

    /**
     * Determine whether the user can update the offerLink.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\OfferLink $offerLink
     * @return mixed
     */
    public function update(User $user, OfferLink $offerLink)
    {
        return $user->equals($offerLink->user);
    }

    /**
     * Determine whether the user can delete the offerLink.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\OfferLink $offerLink
     * @return mixed
     */
    public function delete(User $user, OfferLink $offerLink)
    {
        return $user->equals($offerLink->user);
    }
}
