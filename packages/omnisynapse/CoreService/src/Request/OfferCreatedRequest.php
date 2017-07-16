<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\Offer;

/**
 * Class OfferRequest
 * @package OmniSynapse\CoreService\Request
 */
class OfferCreatedRequest extends Offer implements \JsonSerializable
{
    public function jsonSerialize()
    {

    }

    /**
     * @param string $owner_id
     * @return OfferCreatedRequest
     */
    public function setOwnerId($owner_id) : OfferCreatedRequest
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $name
     * @return OfferCreatedRequest
     */
    public function setName($name) : OfferCreatedRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return OfferCreatedRequest
     */
    public function setDescription($description) : OfferCreatedRequest
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $category_id
     * @return OfferCreatedRequest
     */
    public function setCategoryId($category_id) : OfferCreatedRequest
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param string $geoType
     * @return OfferCreatedRequest
     */
    public function setGeoType($geoType) : OfferCreatedRequest
    {
        $this->geoType = $geoType;
        return $this;
    }

    /**
     * @param float $geoPointLat
     * @return OfferCreatedRequest
     */
    public function setGeoPointLat($geoPointLat) : OfferCreatedRequest
    {
        $this->geoPointLat = $geoPointLat;
        return $this;
    }

    /**
     * @param float $geoPointLong
     * @return OfferCreatedRequest
     */
    public function setGeoPointLong($geoPointLong) : OfferCreatedRequest
    {
        $this->geoPointLong = $geoPointLong;
        return $this;
    }

    /**
     * @param integer $geoRadius
     * @return OfferCreatedRequest
     */
    public function setGeoRadius($geoRadius) : OfferCreatedRequest
    {
        $this->geoRadius = $geoRadius;
        return $this;
    }

    /**
     * @param string $geoCity
     * @return OfferCreatedRequest
     */
    public function setGeoCity($geoCity) : OfferCreatedRequest
    {
        $this->geoCity = $geoCity;
        return $this;
    }

    /**
     * @param string $geoCountry
     * @return OfferCreatedRequest
     */
    public function setGeoCountry($geoCountry) : OfferCreatedRequest
    {
        $this->geoCountry = $geoCountry;
        return $this;
    }

    /**
     * @param integer $limitsOffers
     * @return OfferCreatedRequest
     */
    public function setLimitsOffers($limitsOffers) : OfferCreatedRequest
    {
        $this->limitsOffers = $limitsOffers;
        return $this;
    }

    /**
     * @param integer $limitsPerDay
     * @return OfferCreatedRequest
     */
    public function setLimitsPerDay($limitsPerDay) : OfferCreatedRequest
    {
        $this->limitsPerDay = $limitsPerDay;
        return $this;
    }

    /**
     * @param integer $limitsPerUser
     * @return OfferCreatedRequest
     */
    public function setLimitsPerUser($limitsPerUser) : OfferCreatedRequest
    {
        $this->limitsPerUser = $limitsPerUser;
        return $this;
    }

    /**
     * @param integer $limitsMinLevel
     * @return OfferCreatedRequest
     */
    public function setLimitsMinLevel($limitsMinLevel) : OfferCreatedRequest
    {
        $this->limitsMinLevel = $limitsMinLevel;
        return $this;
    }

    /**
     * @param float $reward
     * @return OfferCreatedRequest
     */
    public function setReward($reward) : OfferCreatedRequest
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param string $start_date
     * @return OfferCreatedRequest
     */
    public function setStartDate($start_date) : OfferCreatedRequest
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @param string $end_date
     * @return OfferCreatedRequest
     */
    public function setEndDate($end_date) : OfferCreatedRequest
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @param string $start_time
     * @return OfferCreatedRequest
     */
    public function setStartTime($start_time) : OfferCreatedRequest
    {
        $this->start_time = $start_time;
        return $this;
    }

    /**
     * @param string $end_time
     * @return OfferCreatedRequest
     */
    public function setEndTime($end_time) : OfferCreatedRequest
    {
        $this->end_time = $end_time;
        return $this;
    }
}