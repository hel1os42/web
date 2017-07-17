<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferRedemption;
use OmniSynapse\CoreService\Job\OfferUpdated;
use OmniSynapse\CoreService\Job\SendNau;
use OmniSynapse\CoreService\Job\UserCreated;
use OmniSynapse\CoreService\Request\OfferCreatedRequest;
use OmniSynapse\CoreService\Request\OfferRedemptionRequest;
use OmniSynapse\CoreService\Request\OfferUpdatedRequest;
use OmniSynapse\CoreService\Request\SendNauRequest;
use OmniSynapse\CoreService\Request\UserCreatedRequest;

class CoreService implements CoreServiceInterface
{
    /** @var array */
    protected $config;

    /**
     * CoreService constructor.
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
    }

    /**
     * @param OfferCreatedRequest $offer
     * @return OfferCreated
     */
    public function offerCreated(OfferCreatedRequest $offer) : OfferCreated
    {
        return new OfferCreated($offer);
    }

    /**
     * @param OfferRedemptionRequest $redemption
     * @return OfferRedemption
     */
    public function offerRedemption(OfferRedemptionRequest $redemption) : OfferRedemption
    {
        return new OfferRedemption($redemption);
    }

    /**
     * @param OfferUpdatedRequest $offer
     * @return OfferUpdated
     */
    public function offerUpdated(OfferUpdatedRequest $offer) : OfferUpdated
    {
        return new OfferUpdated($offer);
    }

    /**
     * @param SendNauRequest $nau
     * @return SendNau
     */
    public function sendNau(SendNauRequest $nau) : SendNau
    {
        return new SendNau($nau);
    }

    /**
     * @param UserCreatedRequest $user
     * @return UserCreated
     */
    public function userCreated(UserCreatedRequest $user) : UserCreated
    {
        return new UserCreated($user);
    }
}