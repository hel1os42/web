<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferUpdatedRequest;
use OmniSynapse\CoreService\Response\OfferUpdatedResponse;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 *
 * @property string id
 */
class OfferUpdated extends Job
{
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
        return '/offer/'.$this->getId();
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new OfferUpdatedRequest();
    }

    /**
     * @return OfferUpdatedResponse
     */
    protected function getResponseClass()
    {
        return new OfferUpdatedResponse();
    }
}