<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferUpdatedRequest;
use OmniSynapse\CoreService\Response\OfferUpdatedResponse;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends Job
{
    /**
     * OfferUpdated constructor.
     * @param OfferUpdatedRequest $offer
     */
    public function __construct(OfferUpdatedRequest $offer)
    {
        parent::__construct();

        /** @var OfferUpdatedRequest requestObject */
        $this->requestObject = $offer;
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offer/'.$this->requestObject->id;
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferUpdatedRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferUpdatedResponse::class;
    }
}