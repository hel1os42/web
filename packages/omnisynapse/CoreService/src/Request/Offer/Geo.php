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
    const TYPE_WORLD   = 'world';
    const TYPE_COUNTRY = 'country';
    const TYPE_CITY    = 'city';
    const TYPE_POINT   = 'point';

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
    public function __construct(Point $point=null, int $radius, string $city, string $country)
    {
        $this->setRadius($radius)
            ->setCity($city)
            ->setCountry($country);

        $type = self::TYPE_WORLD;
        $this->setPoint($point);

        if (null !== $point) {
            $type = self::TYPE_POINT;
        } elseif (!empty($city) && !empty($country)) {
            $type = self::TYPE_CITY;
        } elseif (!empty($country)) {
            $type = self::TYPE_COUNTRY;
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
            'point'     => null !== $this->getPoint()
                ? $this->getPoint()->jsonSerialize()
                : null,
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
     * @return Point|null
     */
    public function getPoint()
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
    public function setPoint(Point $point=null) : Geo
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
