<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
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
     * OfferRedemption constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct();

        /** @var OfferRedemptionRequest requestObject */
        $this->requestObject = $this->getRequestObject()
            ->setId($offer->id)
            ->setUserId($offer->user_id);
    }

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
        return '/offers/'.$this->requestObject->id.'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferRedemptionRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferRedemptionResponse::class;
    }
}