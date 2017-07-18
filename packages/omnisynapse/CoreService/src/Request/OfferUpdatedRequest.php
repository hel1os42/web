<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Offer\Limits;
use App\Models\Offer\Geo;
use Carbon\Carbon;

/**
 * Class OfferUpdatedRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
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
class OfferUpdatedRequest implements \JsonSerializable
{
    /** @var string */
    public $id;

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
     * @param string $id
     * @return OfferUpdatedRequest
     */
    public function setId(string $id) : OfferUpdatedRequest
    {
        $this->id = $id;
        return $this;
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
     * @param Geo $geo
     * @return OfferUpdatedRequest
     */
    public function setGeo(Geo $geo) : OfferUpdatedRequest
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * @param Limits $limits
     * @return OfferUpdatedRequest
     */
    public function setLimits(Limits $limits) : OfferUpdatedRequest
    {
        $this->limits = $limits;
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