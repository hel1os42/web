<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\Offer as OfferRequest;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends Job
{
    /**
     * OfferCreated constructor.
     *
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        /** @var OfferRequest requestObject */
        $this->requestObject = new OfferRequest($offer);
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
        return '/offers';
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
        return OfferResponse::class;
    }
}
