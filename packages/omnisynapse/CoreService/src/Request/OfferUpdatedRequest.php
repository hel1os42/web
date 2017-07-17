<?php

namespace OmniSynapse\CoreService\Request;

use Carbon\Carbon;
use OmniSynapse\CoreService\Entity\Offer;

class OfferUpdatedRequest extends Offer implements \JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'owner_id'          => $this->owner_id,
            'name'              => $this->name,
            'description'       => $this->description,
            'category_id'       => $this->category_id,
            'geo'               => [
                'type'          => $this->geoType,
                'point'         => [
                    'lat'       => $this->geoPointLat,
                    'long'      => $this->geoPointLong,
                ],
                'radius'        => $this->geoRadius,
                'city'          => $this->geoCity,
                'country'       => $this->geoCountry,
            ],
            'limits'            => [
                'offers'        => $this->limitsOffers,
                'per_day'       => $this->limitsPerDay,
                'per_user'      => $this->limitsPerUser,
                'min_level'     => $this->limitsMinLevel,
            ],
            'reward'            => $this->reward,
            'start_date'        => $this->start_date,
            'end_date'          => $this->end_date,
            'start_time'        => $this->start_time,
            'end_time'          => $this->end_time,
        ];
    }

    /**
     * @param string $owner_id
     * @return OfferUpdatedRequest
     */
    public function setOwnerId(string $owner_id) : OfferUpdatedRequest
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $name
     * @return OfferUpdatedRequest
     */
    public function setName(string $name) : OfferUpdatedRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return OfferUpdatedRequest
     */
    public function setDescription(string $description) : OfferUpdatedRequest
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $category_id
     * @return OfferUpdatedRequest
     */
    public function setCategoryId(string $category_id) : OfferUpdatedRequest
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param string $geoType
     * @return OfferUpdatedRequest
     */
    public function setGeoType(string $geoType) : OfferUpdatedRequest
    {
        $this->geoType = $geoType;
        return $this;
    }

    /**
     * @param float $geoPointLat
     * @return OfferUpdatedRequest
     */
    public function setGeoPointLat(float $geoPointLat) : OfferUpdatedRequest
    {
        $this->geoPointLat = $geoPointLat;
        return $this;
    }

    /**
     * @param float $geoPointLong
     * @return OfferUpdatedRequest
     */
    public function setGeoPointLong(float $geoPointLong) : OfferUpdatedRequest
    {
        $this->geoPointLong = $geoPointLong;
        return $this;
    }

    /**
     * @param integer $geoRadius
     * @return OfferUpdatedRequest
     */
    public function setGeoRadius(integer $geoRadius) : OfferUpdatedRequest
    {
        $this->geoRadius = $geoRadius;
        return $this;
    }

    /**
     * @param string $geoCity
     * @return OfferUpdatedRequest
     */
    public function setGeoCity(string $geoCity) : OfferUpdatedRequest
    {
        $this->geoCity = $geoCity;
        return $this;
    }

    /**
     * @param string $geoCountry
     * @return OfferUpdatedRequest
     */
    public function setGeoCountry(string $geoCountry) : OfferUpdatedRequest
    {
        $this->geoCountry = $geoCountry;
        return $this;
    }

    /**
     * @param integer $limitsOffers
     * @return OfferUpdatedRequest
     */
    public function setLimitsOffers(integer $limitsOffers) : OfferUpdatedRequest
    {
        $this->limitsOffers = $limitsOffers;
        return $this;
    }

    /**
     * @param integer $limitsPerDay
     * @return OfferUpdatedRequest
     */
    public function setLimitsPerDay(integer $limitsPerDay) : OfferUpdatedRequest
    {
        $this->limitsPerDay = $limitsPerDay;
        return $this;
    }

    /**
     * @param integer $limitsPerUser
     * @return OfferUpdatedRequest
     */
    public function setLimitsPerUser(integer $limitsPerUser) : OfferUpdatedRequest
    {
        $this->limitsPerUser = $limitsPerUser;
        return $this;
    }

    /**
     * @param integer $limitsMinLevel
     * @return OfferUpdatedRequest
     */
    public function setLimitsMinLevel(integer $limitsMinLevel) : OfferUpdatedRequest
    {
        $this->limitsMinLevel = $limitsMinLevel;
        return $this;
    }

    /**
     * @param float $reward
     * @return OfferUpdatedRequest
     */
    public function setReward(float $reward) : OfferUpdatedRequest
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param Carbon $start_date
     * @return OfferUpdatedRequest
     */
    public function setStartDate(Carbon $start_date) : OfferUpdatedRequest
    {
        $this->start_date = $start_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $end_date
     * @return OfferUpdatedRequest
     */
    public function setEndDate(Carbon $end_date) : OfferUpdatedRequest
    {
        $this->end_date = $end_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $start_time
     * @return OfferUpdatedRequest
     */
    public function setStartTime(Carbon $start_time) : OfferUpdatedRequest
    {
        $this->start_time = $start_time->toTimeString();
        return $this;
    }

    /**
     * @param Carbon $end_time
     * @return OfferUpdatedRequest
     */
    public function setEndTime(Carbon $end_time) : OfferUpdatedRequest
    {
        $this->end_time = $end_time->toTimeString();
        return $this;
    }
}