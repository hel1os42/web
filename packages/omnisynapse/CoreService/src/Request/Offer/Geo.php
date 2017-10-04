<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Geo
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property string type
 * @property null|Point  point
 * @property null|int    radius
 * @property null|string city
 * @property null|string country
 */
class Geo implements \JsonSerializable
{
    const TYPE_WORLD   = 'world';
    const TYPE_COUNTRY = 'country';
    const TYPE_CITY    = 'city';
    const TYPE_POINT   = 'point';

    /** @var string */
    private $type;

    /** @var null|Point */
    private $point;

    /** @var null|int */
    private $radius;

    /** @var null|string */
    private $city;

    /** @var null|string */
    private $country;

    /**
     * Geo constructor.
     *
     * @param Point|null  $point
     * @param int|null    $radius
     * @param null|string $city
     * @param null|string $country
     */
    public function __construct(?Point $point, ?int $radius, ?string $city, ?string $country)
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
            'type'    => $this->getType(),
            'point'   => null !== $this->getPoint()
                ? $this->getPoint()->jsonSerialize()
                : null,
            'radius'  => $this->getRadius(),
            'city'    => $this->getCity(),
            'country' => $this->getCountry(),
        ];
    }

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return null|Point
     */
    public function getPoint(): ?Point
    {
        return $this->point;
    }

    /**
     * @return int|null
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $type
     *
     * @return Geo
     */
    public function setType(string $type): Geo
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param Point|null $point
     *
     * @return Geo
     */
    public function setPoint(?Point $point): Geo
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @param null|string $radius
     *
     * @return Geo
     */
    public function setRadius(?string $radius): Geo
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * @param null|string $city
     *
     * @return Geo
     */
    public function setCity(?string $city): Geo
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param null|string $country
     *
     * @return Geo
     */
    public function setCountry(?string $country): Geo
    {
        $this->country = $country;

        return $this;
    }
}
