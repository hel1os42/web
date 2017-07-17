<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Entity\Offer;
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
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct();

        /** @var OfferCreatedRequest requestObject */
        $this->requestObject = (new OfferCreatedRequest)
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