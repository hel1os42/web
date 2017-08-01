<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Point
 * @package OmniSynapse\CoreService\Response
 */
class Point implements \JsonSerializable
{
    /** @var float */
    public $lat;

    /** @var float */
    public $lon;

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
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLon(): float
    {
        return $this->lon;
    }
}
