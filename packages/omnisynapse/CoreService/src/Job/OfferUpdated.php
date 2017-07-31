<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForUpdate;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends Job
{
    /**
     * OfferUpdated constructor.
     *
     * @param Offer $offer
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Offer $offer, \GuzzleHttp\Client $client=null)
    {
        $this->client = $client;

        /** @var OfferForUpdate requestObject */
        $this->requestObject = new OfferForUpdate($offer);
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
        return '/offers/'.$this->requestObject->offerId;
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
