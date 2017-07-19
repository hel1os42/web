<?php

namespace OmniSynapse\CoreService\Job;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\Offer;

// TODO: project models

/**
 * Class OfferCreated
 * @package OmniSynapse\CoreService\Job
 */
class OfferCreated extends Job
{
    /**
     * OfferCreated constructor.
     * @param XXX $offer
     */
    public function __construct(XXX $offer)
    {
        parent::__construct();

        /** @var Offer requestObject */
        $this->requestObject = (new Offer())
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
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return Offer::class;
    }

    /**
     * @param Response $response
     */
    public function handleError(Response $response)
    {
        // TODO: handler errors
    }
}