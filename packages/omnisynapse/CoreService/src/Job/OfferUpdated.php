<?php

namespace OmniSynapse\CoreService\Job;

use App\Mail\OfferUpdatedFail;
use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Request\OfferForUpdate;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends AbstractJob
{
    /** @var OfferForUpdate */
    private $requestObject;

    /** @var Offer */
    private $offer;

    /**
     * OfferUpdated constructor.
     *
     * @param Offer $offer
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Offer $offer, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        $this->offer = $offer;

        /** @var OfferForUpdate requestObject */
        $this->requestObject = new OfferForUpdate($offer);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers/'.$this->requestObject->offerId;
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

    /**
     * @param RequestException|\InvalidArgumentException|\JsonMapper_Exception|null $exception
     */
    public function fail($exception = null)
    {
        Mail::to($this->offer->getOwner()->getEmail())
            ->queue(new OfferUpdatedFail($this->offer));
    }
}
