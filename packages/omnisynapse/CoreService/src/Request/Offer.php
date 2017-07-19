<?php

namespace OmniSynapse\CoreService\Request;

use Carbon\Carbon;
use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Limits;

/**
 * Class Offer
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
class Offer implements \JsonSerializable
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
     * @return Offer
     */
    public function setOwnerId(string $owner_id) : Offer
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $name
     * @return Offer
     */
    public function setName(string $name) : Offer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return Offer
     */
    public function setDescription(string $description) : Offer
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $category_id
     * @return Offer
     */
    public function setCategoryId(string $category_id) : Offer
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param Geo $geo
     * @return Offer
     */
    public function setGeo(Geo $geo) : Offer
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * @param Limits $limits
     * @return Offer
     */
    public function setLimits(Limits $limits) : Offer
    {
        $this->limits = $limits;
        return $this;
    }

    /**
     * @param float $reward
     * @return Offer
     */
    public function setReward(float $reward) : Offer
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param Carbon $start_date
     * @return Offer
     */
    public function setStartDate(Carbon $start_date) : Offer
    {
        $this->start_date = $start_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $end_date
     * @return Offer
     */
    public function setEndDate(Carbon $end_date) : Offer
    {
        $this->end_date = $end_date->toDateString();
        return $this;
    }

    /**
     * @param Carbon $start_time
     * @return Offer
     */
    public function setStartTime(Carbon $start_time) : Offer
    {
        $this->start_time = $start_time->toTimeString();
        return $this;
    }

    /**
     * @param Carbon $end_time
     * @return Offer
     */
    public function setEndTime(Carbon $end_time) : Offer
    {
        $this->end_time = $end_time->toTimeString();
        return $this;
    }
}