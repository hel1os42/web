<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Offer\Limits;
use App\Models\Offer\Geo;
use Carbon\Carbon;

/**
 * Class OfferRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string owner_id
 * @property string name
 * @property string description
 * @property string category_id
 *
 * @property float reward
 * @property Carbon start_date
 * @property Carbon end_date
 * @property Carbon start_time
 * @property Carbon end_time
 */
class OfferCreatedRequest implements \JsonSerializable
{
    /** @var string */
    public $owner_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $category_id;

    /** @var Geo */
    public $geo;

    /** @var Limits */
    public $limits;

    /** @var float */
    public $reward;

    /** @var Carbon */
    public $start_date;

    /** @var Carbon */
    public $end_date;

    /** @var Carbon */
    public $start_time;

    /** @var Carbon */
    public $end_time;

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
                'type'          => $this->geo->getType(),
                'point'         => [
                    'lat'       => $this->geo->getPoint()->getLat(),
                    'long'      => $this->geo->getPoint()->getLong(),
                ],
                'radius'        => $this->geo->getRadius(),
                'city'          => $this->geo->getCity(),
                'country'       => $this->geo->getCountry(),
            ],
            'limits'            => [
                'offers'        => $this->limits->getOffers(),
                'per_day'       => $this->limits->getPerDay(),
                'per_user'      => $this->limits->getPerUser(),
                'min_level'     => $this->limits->getMinLevel(),
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
     * @param Geo $geo
     * @return OfferCreatedRequest
     */
    public function setGeo(Geo $geo) : OfferCreatedRequest
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * @param Limits $limits
     * @return OfferCreatedRequest
     */
    public function setLimits(Limits $limits) : OfferCreatedRequest
    {
        $this->limits = $limits;
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