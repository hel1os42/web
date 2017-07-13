<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\UserCreated;

class CoreService
{
    /**
     * @return OfferCreated
     */
    public function offerCreated()
    {
        return new OfferCreated();
    }

    /**
     * @return OfferRedemption
     */
    public function offerRedemption()
    {
        return new OfferRedemption();
    }

    /**
     * @return OfferUpdated
     */
    public function offerUpdated()
    {
        return new OfferUpdated();
    }

    /**
     * @return SendNau
     */
    public function sendNau()
    {
        return new SendNau();
    }

    /**
     * @return UserCreated
     */
    public function userCreated()
    {
        return new UserCreated();
    }
}