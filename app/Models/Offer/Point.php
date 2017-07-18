<?php

namespace App\Models\Offer;

/**
 * Class Point
 * @package OmniSynapse\CoreService\Point
 *
 * @property float lat
 * @property float long
 */
class Point implements \JsonSerializable
{
    /** @var float */
    private $lat;

    /** @var float */
    private $long;

    /**
     * Point constructor.
     * @param float $lat
     * @param float $long
     */
    public function __construct(float $lat, float $long)
    {
        $this->setLat($lat)
            ->setLong($long);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'lat'   => $this->getLat(),
            'long'  => $this->getLong(),
        ];
    }

    /**
     * @return float
     */
    public function getLat() : float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLong() : float
    {
        return $this->long;
    }

    /**
     * @param float $lat
     * @return Point
     */
    public function setLat(float $lat) : Point
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @param float $long
     * @return Point
     */
    public function setLong(float $long) : Point
    {
        $this->long = $long;
        return $this;
    }
}