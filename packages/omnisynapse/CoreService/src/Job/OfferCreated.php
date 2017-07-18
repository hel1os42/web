<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
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
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        parent::__construct();

        /** @var OfferCreatedRequest requestObject */
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