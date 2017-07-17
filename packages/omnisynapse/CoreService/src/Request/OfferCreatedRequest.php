<?php

namespace OmniSynapse\CoreService\Request;

use Carbon\Carbon;
use OmniSynapse\CoreService\Entity\Offer;

/**
 * Class OfferRequest
 * @package OmniSynapse\CoreService\Request
 */
class OfferCreatedRequest extends Offer implements \JsonSerializable
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
     * @return OfferCreatedRequest
     */
    public function setOwnerId(string $owner_id) : OfferCreatedRequest
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $name
     * @return OfferCreatedRequest
     */
    public function setName(string $name) : OfferCreatedRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return OfferCreatedRequest
     */
    public function setDescription(string $description) : OfferCreatedRequest
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $category_id
     * @return OfferCreatedRequest
     */
    public function setCategoryId(string $category_id) : OfferCreatedRequest
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param string $geoType
     * @return OfferCreatedRequest
     */
    public function setGeoType(string $geoType) : OfferCreatedRequest
    {
        $this->geoType = $geoType;
        return $this;
    }

    /**
     * @param float $geoPointLat
     * @return OfferCreatedRequest
     */
    public function setGeoPointLat(float $geoPointLat) : OfferCreatedRequest
    {
        $this->geoPointLat = $geoPointLat;
        return $this;
    }

    /**
     * @param float $geoPointLong
     * @return OfferCreatedRequest
     */
    public function setGeoPointLong(float $geoPointLong) : OfferCreatedRequest
    {
        $this->geoPointLong = $geoPointLong;
        return $this;
    }

    /**
     * @param integer $geoRadius
     * @return OfferCreatedRequest
     */
    public function setGeoRadius(integer $geoRadius) : OfferCreatedRequest
    {
        $this->geoRadius = $geoRadius;
        return $this;
    }

    /**
     * @param string $geoCity
     * @return OfferCreatedRequest
     */
    public function setGeoCity(string $geoCity) : OfferCreatedRequest
    {
        $this->geoCity = $geoCity;
        return $this;
    }

    /**
     * @param string $geoCountry
     * @return OfferCreatedRequest
     */
    public function setGeoCountry(string $geoCountry) : OfferCreatedRequest
    {
        $this->geoCountry = $geoCountry;
        return $this;
    }

    /**
     * @param integer $limitsOffers
     * @return OfferCreatedRequest
     */
    public function setLimitsOffers(integer $limitsOffers) : OfferCreatedRequest
    {
        $this->limitsOffers = $limitsOffers;
        return $this;
    }

    /**
     * @param integer $limitsPerDay
     * @return OfferCreatedRequest
     */
    public function setLimitsPerDay(integer $limitsPerDay) : OfferCreatedRequest
    {
        $this->limitsPerDay = $limitsPerDay;
        return $this;
    }

    /**
     * @param integer $limitsPerUser
     * @return OfferCreatedRequest
     */
    public function setLimitsPerUser(integer $limitsPerUser) : OfferCreatedRequest
    {
        $this->limitsPerUser = $limitsPerUser;
        return $this;
    }

    /**
     * @param integer $limitsMinLevel
     * @return OfferCreatedRequest
     */
    public function setLimitsMinLevel(integer $limitsMinLevel) : OfferCreatedRequest
    {
        $this->limitsMinLevel = $limitsMinLevel;
        return $this;
    }

    /**
     * @param float $reward
     * @return OfferCreatedRequest
     */
    public function setReward(float $reward) : OfferCreatedRequest
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param Carbon $start_date
     * @return OfferCreatedRequest
     */
    public function setStartDate(Carbon $start_date) : OfferCreatedRequest
    {
        $this->start_date = $start_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $end_date
     * @return OfferCreatedRequest
     */
    public function setEndDate(Carbon $end_date) : OfferCreatedRequest
    {
        $this->end_date = $end_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $start_time
     * @return OfferCreatedRequest
     */
    public function setStartTime(Carbon $start_time) : OfferCreatedRequest
    {
        $this->start_time = $start_time->toTimeString();
        return $this;
    }

    /**
     * @param Carbon $end_time
     * @return OfferCreatedRequest
     */
    public function setEndTime(Carbon $end_time) : OfferCreatedRequest
    {
        $this->end_time = $end_time->toTimeString();
        return $this;
    }
}