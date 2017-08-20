<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Geo
 * @package OmniSynapse\CoreService\Response
 */
class Geo implements \JsonSerializable
{
    /** @var string */
    public $type;

    /** @var Point */
    public $point;

    /** @var int */
    public $radius;

    /** @var string */
    public $city;

    /** @var string */
    public $country;

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
    public function getType(): string
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
    public function getRadius(): int
    {
        return $this->radius;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}
