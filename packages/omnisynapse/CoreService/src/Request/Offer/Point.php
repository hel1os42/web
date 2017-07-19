<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Point
 * @package OmniSynapse\CoreService\Point
 *
 * @property float lat
 * @property float lon
 */
class Point implements \JsonSerializable
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    /**
     * Point constructor.
     * @param float $lat
     * @param float $lon
     */
    public function __construct(float $lat, float $lon)
    {
        $this->setLat($lat)
            ->setLon($lon);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'lat'   => $this->getLat(),
            'lon'   => $this->getLon(),
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
    public function getLon() : float
    {
        return $this->lon;
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
     * @param float $lon
     * @return Point
     */
    public function setLon(float $lon) : Point
    {
        $this->lon = $lon;
        return $this;
    }
}