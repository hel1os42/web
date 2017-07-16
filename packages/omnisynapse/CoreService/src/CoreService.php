<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\UserCreated;
use OmniSynapse\CoreService\Request\UserCreatedRequest;

class CoreService
{
    /**
     * @return OfferCreated
     */
    public function offerCreated() : OfferCreated
    {
        return new OfferCreated();
    }

    /**
     * @return OfferRedemption
     */
    public function offerRedemption() : OfferRedemption
    {
        return new OfferRedemption();
    }

    /**
     * @return OfferUpdated
     */
    public function offerUpdated() : OfferUpdated
    {
        return new OfferUpdated();
    }

    /**
     * @return SendNau
     */
    public function sendNau() : SendNau
    {
        return new SendNau();
    }

    /**
     * @param UserCreatedRequest $user
     * @param UserCreatedRequest|null $referrer
     * @return UserCreated
     */
    public function userCreated(UserCreatedRequest $user, UserCreatedRequest $referrer = null) : UserCreated
    {
        return new UserCreated($user, $referrer);
    }
}