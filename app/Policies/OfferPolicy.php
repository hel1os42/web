<?php

namespace App\Policies;

use App\Models\Role;
use App\Repositories\OfferRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    private $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * @return bool
     */
    public function index()
    {
        return auth()->user()->hasRole(Role::ROLE_ADVERTISER) ? true : false;
    }

    /**
     * @return bool
     */
    public function create()
    {
        return auth()->user()->hasRole(Role::ROLE_ADVERTISER) ? true : false;
    }

    /**
     * @return bool
     */
    public function store()
    {
        return auth()->user()->hasRole(Role::ROLE_ADVERTISER) ? true : false;
    }

    /**
     * @param string $offerId
     *
     * @return bool
     */
    public function show(string $offerId)
    {
        if(auth()->user()->hasRole(Role::ROLE_ADMIN)){
            return true;
        }

        if(auth()->user()->hasRole(Role::ROLE_CHIEF_ADVERTISER) || auth()->user()->hasRole(Role::ROLE_AGENT)) {
            return false; //check if agent or main advert can view offer
        }

        if(auth()->user()->hasRole(Role::ROLE_ADVERTISER)){
            $offer = $this->offerRepository->find($offerId);
            if($offer !== null && $offer->isOwner(auth()->user())){
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function userIndex()
    {
        return auth()->user()->hasRole(Role::ROLE_USER) ? true : false;
    }

    /**
     * @return bool
     */
    public function userShow()
    {
        return auth()->user()->hasRole(Role::ROLE_USER) ? true : false;
    }
}
