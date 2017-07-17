<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;
use OmniSynapse\CoreService\Entity\Offer;

class OfferUpdatedResponse extends Offer
{
    /**
     * @return string
     */
    public function getOwnerId() : string
    {
        return $this->owner_id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategoryId() : string
    {
        return $this->category_id;
    }

    /**
     * @return string
     */
    public function getGeoType() : string
    {
        return $this->geoType;
    }

    /**
     * @return float
     */
    public function getGeoPointLat() : float
    {
        return $this->geoPointLat;
    }

    /**
     * @return float
     */
    public function getGeoPointLong() : float
    {
        return $this->geoPointLong;
    }

    /**
     * @return integer
     */
    public function getGeoRadius() : integer
    {
        return $this->geoRadius;
    }

    /**
     * @return string
     */
    public function getGeoCity() : string
    {
        return $this->geoCity;
    }

    /**
     * @return string
     */
    public function getGeoCountry() : string
    {
        return $this->geoCountry;
    }

    /**
     * @return integer
     */
    public function getLimitsOffers() : integer
    {
        return $this->limitsOffers;
    }

    /**
     * @return integer
     */
    public function getLimitsPerDay() : integer
    {
        return $this->limitsPerDay;
    }

    /**
     * @return integer
     */
    public function getLimitsPerUser() : integer
    {
        return $this->limitsPerUser;
    }

    /**
     * @return integer
     */
    public function getLimitsMinLevel() : integer
    {
        return $this->limitsMinLevel;
    }

    /**
     * @return float
     */
    public function getReward() : float
    {
        return $this->reward;
    }

    /**
     * @return Carbon
     */
    public function getStartDate() : Carbon
    {
        return $this->start_date;
    }

    /**
     * @return Carbon
     */
    public function getEndDate() : Carbon
    {
        return $this->end_date;
    }

    /**
     * @return Carbon
     */
    public function getStartTime() : Carbon
    {
        return $this->start_time;
    }

    /**
     * @return Carbon
     */
    public function getEndTime() : Carbon
    {
        return $this->end_time;
    }
}