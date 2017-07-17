<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Entity\Offer;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferUpdatedRequest;
use OmniSynapse\CoreService\Response\OfferUpdatedResponse;

/**
 * Class OfferUpdated
 * @package OmniSynapse\CoreService\Job
 */
class OfferUpdated extends Job
{
    protected $offer;

    /**
     * OfferUpdated constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct();

        /** @var Offer offer */
        $this->offer = $offer;

        /** @var OfferUpdatedRequest requestObject */
        $this->requestObject = (new OfferUpdatedRequest)
            ->setOwnerId($offer->owner_id)
            ->setName($offer->name)
            ->setDescription($offer->description)
            ->setCategoryId($offer->category_id)
            ->setGeoType($offer->geoType)
            ->setGeoPointLat($offer->geoPointLat)
            ->setGeoPointLong($offer->geoPointLong)
            ->setGeoRadius($offer->geoRadius)
            ->setGeoCity($offer->geoCity)
            ->setGeoCountry($offer->geoCountry)
            ->setLimitsOffers($offer->limitsOffers)
            ->setLimitsPerDay($offer->limitsPerDay)
            ->setLimitsPerUser($offer->limitsPerUser)
            ->setLimitsMinLevel($offer->limitsMinLevel)
            ->setReward($offer->reward)
            ->setStartDate($offer->start_date)
            ->setEndDate($offer->end_date)
            ->setStartTime($offer->start_time)
            ->setEndTime($offer->end_time);
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
        return '/offer/'.$this->offer->id;
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