<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
 * @property string owner_id
 * @property string name
 * @property string description
 * @property string category_id
 *
 * @property string geoType
 * @property float geoPointLat
 * @property float geoPointLong
 * @property integer geoRadius
 * @property string geoCity
 * @property string geoCountry
 *
 * @property integer limitsOffers
 * @property integer limitsPerDay
 * @property integer limitsPerUser
 * @property integer limitsMinLevel
 *
 * @property float reward
 * @property string start_date
 * @property string end_date
 * @property string start_time
 * @property string end_time
 */
class OfferRequest {
    /** @var string */
    private $owner_id = null;

    /** @var string */
    private $name = null;

    /** @var string */
    private $description = null;

    /** @var string */
    private $category_id = null;

    /** @var string */
    private $geoType = null;

    /** @var float */
    private $geoPointLat = 0.0;

    /** @var float */
    private $geoPointLong = 0.0;

    /** @var integer */
    private $geoRadius = 0;

    /** @var string */
    private $geoCity = null;

    /** @var string */
    private $geoCountry = null;

    /** @var integer */
    private $limitsOffers = 0;

    /** @var integer */
    private $limitsPerDay = 0;

    /** @var integer */
    private $limitsPerUser = 0;

    /** @var integer */
    private $limitsMinLevel = 0;

    /** @var float */
    private $reward = 0.0;

    /** @var string */
    private $start_date = null;

    /** @var string */
    private $end_date = null;

    /** @var string */
    private $start_time = null;

    /** @var string */
    private $end_time = null;

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @return string
     */
    public function getGeoType()
    {
        return $this->geoType;
    }

    /**
     * @return float
     */
    public function getGeoPointLat()
    {
        return $this->geoPointLat;
    }

    /**
     * @return float
     */
    public function getGeoPointLong()
    {
        return $this->geoPointLong;
    }

    /**
     * @return integer
     */
    public function getGeoRadius()
    {
        return $this->geoRadius;
    }

    /**
     * @return string
     */
    public function getGeoCity()
    {
        return $this->geoCity;
    }

    /**
     * @return string
     */
    public function getGeoCountry()
    {
        return $this->geoCountry;
    }

    /**
     * @return integer
     */
    public function getLimitsOffers()
    {
        return $this->limitsOffers;
    }

    /**
     * @return integer
     */
    public function getLimitsPerDay()
    {
        return $this->limitsPerDay;
    }

    /**
     * @return integer
     */
    public function getLimitsPerUser()
    {
        return $this->limitsPerUser;
    }

    /**
     * @return integer
     */
    public function getLimitsMinLevel()
    {
        return $this->limitsMinLevel;
    }

    /**
     * @return float
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param string $owner_id
     * @return $this
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $category_id
     * @return $this
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @param string $geoType
     * @return $this
     */
    public function setGeoType($geoType)
    {
        $this->geoType = $geoType;
        return $this;
    }

    /**
     * @param float $geoPointLat
     * @return $this
     */
    public function setGeoPointLat($geoPointLat)
    {
        $this->geoPointLat = $geoPointLat;
        return $this;
    }

    /**
     * @param float $geoPointLong
     * @return $this
     */
    public function setGeoPointLong($geoPointLong)
    {
        $this->geoPointLong = $geoPointLong;
        return $this;
    }

    /**
     * @param integer $geoRadius
     * @return $this
     */
    public function setGeoRadius($geoRadius)
    {
        $this->geoRadius = $geoRadius;
        return $this;
    }

    /**
     * @param string $geoCity
     * @return $this
     */
    public function setGeoCity($geoCity)
    {
        $this->geoCity = $geoCity;
        return $this;
    }

    /**
     * @param string $geoCountry
     * @return $this
     */
    public function setGeoCountry($geoCountry)
    {
        $this->geoCountry = $geoCountry;
        return $this;
    }

    /**
     * @param integer $limitsOffers
     * @return $this
     */
    public function setLimitsOffers($limitsOffers)
    {
        $this->limitsOffers = $limitsOffers;
        return $this;
    }

    /**
     * @param integer $limitsPerDay
     * @return $this
     */
    public function setLimitsPerDay($limitsPerDay)
    {
        $this->limitsPerDay = $limitsPerDay;
        return $this;
    }

    /**
     * @param integer $limitsPerUser
     * @return $this
     */
    public function setLimitsPerUser($limitsPerUser)
    {
        $this->limitsPerUser = $limitsPerUser;
        return $this;
    }

    /**
     * @param integer $limitsMinLevel
     * @return $this
     */
    public function setLimitsMinLevel($limitsMinLevel)
    {
        $this->limitsMinLevel = $limitsMinLevel;
        return $this;
    }

    /**
     * @param float $reward
     * @return $this
     */
    public function setReward($reward)
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param string $start_date
     * @return $this
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * @param string $end_date
     * @return $this
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * @param string $start_time
     * @return $this
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
        return $this;
    }

    /**
     * @param string $end_time
     * @return $this
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
        return $this;
    }
}