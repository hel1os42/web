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

    /** @var Point|null */
    public $point;

    /** @var int|null */
    public $radius;

    /** @var string|null */
    public $city;

    /** @var string|null */
    public $country;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Point|null
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
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }
}
