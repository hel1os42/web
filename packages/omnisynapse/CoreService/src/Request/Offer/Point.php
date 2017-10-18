<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Point
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property null|float lat
 * @property null|float lon
 */
class Point implements \JsonSerializable
{
    /** @var null|float */
    private $lat;

    /** @var null|float */
    private $lon;

    /**
     * Point constructor.
     *
     * @param float|null $lat
     * @param float|null $lon
     */
    public function __construct(?float $lat, ?float $lon)
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
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @return float|null
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * @param float|null $lat
     *
     * @return Point
     */
    public function setLat(?float $lat): Point
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @param float|null $lon
     *
     * @return Point
     */
    public function setLon(?float $lon): Point
    {
        $this->lon = $lon;
        return $this;
    }
}
