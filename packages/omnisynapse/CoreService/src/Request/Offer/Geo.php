<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Geo
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property string      type
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
    private $type = null;

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
        $this->setPoint($point)
             ->setRadius($radius)
             ->setCountry($country)
             ->setCity($city);
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
        return $this->identifyType();
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

    /**
     * Detects current Geo type
     *
     * @return string
     */
    protected function identifyType(): string
    {
        $type = self::TYPE_WORLD;
        if (null !== $this->getPoint()) {
            $type = self::TYPE_POINT;
            if (null !== $this->getCountry()) {
                $type = self::TYPE_COUNTRY;
                if (null !== $this->getCity()) {
                    $type = self::TYPE_CITY;
                }
            }
        }

        return $this->type = $type;
    }

    public function __sleep()
    {
        return array_keys($this->jsonSerialize());
    }
}
