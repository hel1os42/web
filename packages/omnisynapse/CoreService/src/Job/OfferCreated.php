<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\Offer as OfferRequest;
use OmniSynapse\CoreService\Response\Offer as OfferResponse;

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
        $point = new OfferRequest\Point($offer->getLatitude(), $offer->getLongitude());
        $geo = new OfferRequest\Geo(null, $point, $offer->getRadius(), $offer->getCity(), $offer->getCountry()); // TODO: null=>geo_type, where is GEO type?
        $limits = new OfferRequest\Limits($offer->getMaxCount(), $offer->getMaxPerDay(), $offer->getMaxForUser(), $offer->getUserLevelMin());

        /** @var OfferRequest requestObject */
        $this->requestObject = (new OfferRequest())
            ->setOwnerId($offer->getAccountId())
            ->setName($offer->getLabel())
            ->setDescription($offer->getDescription())
            ->setCategoryId($offer->getCategoryId())
            ->setGeo($geo)
            ->setLimits($limits)
            ->setReward($offer->getReward())
            ->setStartDate($offer->getStartDate())
            ->setEndDate($offer->getFinishDate())
            ->setStartTime($offer->getStartTime())
            ->setEndTime($offer->getFinishTime());
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
        return '/offers';
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