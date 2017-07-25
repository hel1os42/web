<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Geo
 * @package OmniSynapse\CoreService\Offer
 *
 * @property string type
 * @property Point point
 * @property int radius
 * @property string city
 * @property string country
 */
class Geo implements \JsonSerializable
{
    /** @var string */
    private $type;

    /** @var Point */
    private $point;

    /** @var int */
    private $radius;

    /** @var string */
    private $city;

    /** @var string */
    private $country;

    /**
     * Geo constructor.
     *
     * @param Point $point
     * @param int $radius
     * @param string $city
     * @param string $country
     */
    public function __construct(Point $point, int $radius, string $city, string $country)
    {
        $this->setPoint($point)
            ->setRadius($radius)
            ->setCity($city)
            ->setCountry($country);

        $type = null;

        if ($point->getLat() > 0 && $point->getLon() > 0) {
            $type = 'point';
        } elseif (null !== $city && null !== $country) {
            $type = 'city';
        } elseif (null !== $country) {
            $type = 'country';
        }

        $this->setType($type);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type'      => $this->getType(),
            'point'     => $this->getPoint()->jsonSerialize(),
            'radius'    => $this->getRadius(),
            'city'      => $this->getCity(),
            'country'   => $this->getCountry(),
        ];
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return Point
     */
    public function getPoint() : Point
    {
        return $this->point;
    }

    /**
     * @return int
     */
    public function getRadius() : int
    {
        return $this->radius;
    }

    /**
     * @return string
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountry() : string
    {
        return $this->country;
    }

    /**
     * @param string $type
     * @return Geo
     */
    public function setType(string $type) : Geo
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param Point $point
     * @return Geo
     */
    public function setPoint(Point $point) : Geo
    {
        $this->point = $point;
        return $this;
    }

    /**
     * @param string $radius
     * @return Geo
     */
    public function setRadius(string $radius) : Geo
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * @param string $city
     * @return Geo
     */
    public function setCity(string $city) : Geo
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $country
     * @return Geo
     */
    public function setCountry(string $country) : Geo
    {
        $this->country = $country;
        return $this;
    }
}
