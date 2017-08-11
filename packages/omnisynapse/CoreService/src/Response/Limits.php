<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Response
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Limits implements \JsonSerializable
{
    /** @var int */
    public $offers;

    /** @var int */
    public $per_day;

    /** @var int */
    public $per_user;

    /** @var int */
    public $min_level;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'offers'    => $this->getOffers(),
            'per_day'   => $this->getPerDay(),
            'per_user'  => $this->getPerUser(),
            'min_level' => $this->getMinLevel(),
        ];
    }

    /**
     * @return int
     */
    public function getOffers(): int
    {
        return $this->offers;
    }

    /**
     * @return int
     */
    public function getPerDay(): int
    {
        return $this->per_day;
    }

    /**
     * @return int
     */
    public function getPerUser(): int
    {
        return $this->per_user;
    }

    /**
     * @return int
     */
    public function getMinLevel(): int
    {
        return $this->min_level;
    }
}
