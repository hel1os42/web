<?php

namespace App\Models;

use App\Models\Offer\Limits;
use App\Models\Offer\Geo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Offer
 * @package App\Models
 *
 * @property string id
 * @property string owner_id
 * @property string name
 * @property string description
 * @property string category_id
 *
 * @property array geo
 * @property array limits
 *
 * @property float reward
 * @property Carbon start_date
 * @property Carbon end_date
 * @property Carbon start_time
 * @property Carbon end_time
 * @property Carbon created_at
 */
class Offer extends Model
{
    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

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
     * @return array
     */
    public function geoGeo() : array
    {
        return $this->geo;
    }

    /**
     * @return array
     */
    public function getLimits() : array
    {
        return $this->limits;
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
        return Carbon::parse($this->start_date);
    }

    /**
     * @return Carbon
     */
    public function getEndDate() : Carbon
    {
        return Carbon::parse($this->end_date);
    }

    /**
     * @return Carbon
     */
    public function getStartTime() : Carbon
    {
        return Carbon::parse($this->start_time);
    }

    /**
     * @return Carbon
     */
    public function getEndTime() : Carbon
    {
        return Carbon::parse($this->end_time);
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return $this->created_at;
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
