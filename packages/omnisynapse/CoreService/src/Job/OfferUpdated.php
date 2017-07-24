<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Offer;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Limits;
use OmniSynapse\CoreService\Request\Offer\Point;
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
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $point = new Point($offer->getLatitude(), $offer->getLongitude());
        $geo = new Geo(null, $point, $offer->getRadius(), $offer->getCity(), $offer->getCountry()); // TODO: null=>geo_type, where is GEO type?
        $limits = new Limits($offer->getMaxCount(), $offer->getMaxPerDay(), $offer->getMaxForUser(), $offer->getUserLevelMin());

        /** @var OfferForUpdate requestObject */
        $this->requestObject = (new OfferForUpdate())
            ->setOfferId($offer->getId())
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