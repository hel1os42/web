<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
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
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct();

        /** @var OfferUpdatedRequest requestObject */
        $this->requestObject = $this->getRequestObject()
            ->setOwnerId($offer->getOwnerId())
            ->setName($offer->getName())
            ->setDescription($offer->getDescription())
            ->setCategoryId($offer->getCategoryId())
            ->setGeo($offer->geo)
            ->setLimits($offer->limits)
            ->setReward($offer->getReward())
            ->setStartDate($offer->getStartDate())
            ->setEndDate($offer->getEndDate())
            ->setStartTime($offer->getStartTime())
            ->setEndTime($offer->getEndTime());
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