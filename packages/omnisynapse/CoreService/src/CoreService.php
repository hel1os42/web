<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\UserCreated;

class CoreService
{
    /** @var OfferCreated */
    public $offerCreated;

    /** @var OfferRedemption */
    public $offerRedemption;

    /** @var OfferUpdated */
    public $offerUpdated;

    /** @var SendNau */
    public $sendNau;

    /** @var UserCreated */
    public $userCreated;

    /**
     * CoreService constructor.
     */
    public function __construct()
    {
        $this->offerCreated = new OfferCreated();
        $this->offerRedemption = new OfferRedemption();
        $this->offerUpdated = new OfferUpdated();
        $this->sendNau = new SendNau();
        $this->userCreated = new UserCreated();
    }
}