<?php

namespace OmniSynapse\CoreService\Entity;

use Carbon\Carbon;

/**
 * Class Offer
 * @package OmniSynapse\CoreService\Entity
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
 * @property Carbon start_date
 * @property Carbon end_date
 * @property Carbon start_time
 * @property Carbon end_time
 *
 * @property string user_id
 */
class Offer
{
    /** @var string */
    public $owner_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $category_id;

    /** @var string */
    public $geoType;

    /** @var float */
    public $geoPointLat;

    /** @var float */
    public $geoPointLong;

    /** @var integer */
    public $geoRadius;

    /** @var string */
    public $geoCity;

    /** @var string */
    public $geoCountry;

    /** @var integer */
    public $limitsOffers;

    /** @var integer */
    public $limitsPerDay;

    /** @var integer */
    public $limitsPerUser;

    /** @var integer */
    public $limitsMinLevel;

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

    /** @var string */
    public $user_id;
}