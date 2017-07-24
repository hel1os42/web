<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Redemption;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    /**
     * OfferRedemption constructor.
     * @param Redemption $offer
     */
    public function __construct(Redemption $offer)
    {
        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = (new OfferForRedemptionRequest())
            ->setId($offer->getId())
            ->setUserId($offer->getUserId());
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
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferForRedemptionResponse::class;
    }
}