<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Request\Offer as OfferRequest;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends AbstractJob
{
    /** @var OfferRequest\ */
    private $requestObject;

    /**
     * OfferCreated constructor.
     *
     * @param Offer $offer
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Offer $offer, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        /** @var OfferRequest requestObject */
        $this->requestObject = new OfferRequest($offer);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers';
    }

    /**
     * @return \JsonSerializable
     */
    public function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return OfferResponse::class;
    }
}