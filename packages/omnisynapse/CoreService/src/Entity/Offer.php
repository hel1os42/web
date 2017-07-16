<?php

namespace OmniSynapse\CoreService\Entity;

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
 * @property string start_date
 * @property string end_date
 * @property string start_time
 * @property string end_time
 */
class Offer
{
    /** @var string */
    protected $owner_id = null;

    /** @var string */
    protected $name = null;

    /** @var string */
    protected $description = null;

    /** @var string */
    protected $category_id = null;

    /** @var string */
    protected $geoType = null;

    /** @var float */
    protected $geoPointLat = 0.0;

    /** @var float */
    protected $geoPointLong = 0.0;

    /** @var integer */
    protected $geoRadius = 0;

    /** @var string */
    protected $geoCity = null;

    /** @var string */
    protected $geoCountry = null;

    /** @var integer */
    protected $limitsOffers = 0;

    /** @var integer */
    protected $limitsPerDay = 0;

    /** @var integer */
    protected $limitsPerUser = 0;

    /** @var integer */
    protected $limitsMinLevel = 0;

    /** @var float */
    protected $reward = 0.0;

    /** @var string */
    protected $start_date = null;

    /** @var string */
    protected $end_date = null;

    /** @var string */
    protected $start_time = null;

    /** @var string */
    protected $end_time = null;
}