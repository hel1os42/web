<?php

namespace OmniSynapse\CoreService;

use OmniSynapse\CoreService\Request\OfferCreatedRequest;
use OmniSynapse\CoreService\Request\OfferRedemptionRequest;
use OmniSynapse\CoreService\Request\OfferUpdatedRequest;
use OmniSynapse\CoreService\Request\SendNauRequest;
use OmniSynapse\CoreService\Request\UserCreatedRequest;

interface CoreServiceInterface
{
    /**
     * @param OfferCreatedRequest $offer
     */
    public function offerCreated(OfferCreatedRequest $offer);

    /**
     * @param OfferRedemptionRequest $redemption
     */
    public function offerRedemption(OfferRedemptionRequest $redemption);

    /**
     * @param OfferUpdatedRequest $offer
     */
    public function offerUpdated(OfferUpdatedRequest $offer);

    /**
     * @param SendNauRequest $nau
     */
    public function sendNau(SendNauRequest $nau);

    /**
     * @param UserCreatedRequest $user
     */
    public function userCreated(UserCreatedRequest $user);
}