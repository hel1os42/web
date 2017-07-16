<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offers/'.$this->getId().'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferRedemptionRequest();
    }

    /**
     * @return OfferRedemptionResponse
     */
    protected function getResponseClass()
    {
        return new OfferRedemptionResponse();
    }
}