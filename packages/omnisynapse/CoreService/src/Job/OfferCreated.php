<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferCreatedRequest;
use OmniSynapse\CoreService\Response\OfferCreatedResponse;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends Job
{
    /**
     * OfferCreated constructor.
     * @param OfferCreatedRequest $offer
     */
    public function __construct(OfferCreatedRequest $offer)
    {
        parent::__construct();

        /** @var OfferCreatedRequest requestObject */
        $this->requestObject = $offer;
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
        return '/offer';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferCreatedRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferCreatedResponse::class;
    }
}