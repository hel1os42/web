<?php

namespace OmniSynapse\CoreService;

use App\Models\Offer;
use App\Models\Redemption;
use App\Models\User;

interface CoreServiceInterface
{
    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerCreated(Offer $offer) : Job;

    /**
     * @param Redemption $redemption
     * @return Job
     */
    public function offerRedemption(Redemption $redemption) : Job;

    /**
     * @param Offer $offer
     * @return Job
     */
    public function offerUpdated(Offer $offer) : Job;

    /**
     * @return Job
     */
    public function sendNau() : Job;

    /**
     * @param User $user
     * @return Job
     */
    public function userCreated(User $user) : Job;
}