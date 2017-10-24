<?php

namespace OmniSynapse\CoreService\Response;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Response
 *
 * @property int|null offers
 * @property int|null per_day
 * @property int|null per_user
 * @property int|null per_user_per_day
 * @property int|null per_user_per_week
 * @property int|null per_user_per_month
 * @property int      min_level
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Limits implements \JsonSerializable
{
    /** @var int|null */
    public $offers;

    /** @var int|null */
    public $per_day;

    /** @var int|null */
    public $per_user;

    /**
     * @var int|null
     */
    public $per_user_per_day;

    /**
     * @var int|null
     */
    public $per_user_per_week;

    /**
     * @var int|null
     */
    public $per_user_per_month;

    /** @var int */
    public $min_level;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'offers'             => $this->getOffers(),
            'per_day'            => $this->getPerDay(),
            'per_user'           => $this->getPerUser(),
            'per_user_per_day'   => $this->getPerUserPerDay(),
            'per_user_per_week'  => $this->getPerUserPerWeek(),
            'per_user_per_month' => $this->getPerUserPerMonth(),
            'min_level'          => $this->getMinLevel(),
        ];
    }

    /**
     * @return int|null
     */
    public function getOffers(): ?int
    {
        return $this->offers;
    }

    /**
     * @return int|null
     */
    public function getPerDay(): ?int
    {
        return $this->per_day;
    }

    /**
     * @return int|null
     */
    public function getPerUser(): ?int
    {
        return $this->per_user;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerDay(): ?int
    {
        return $this->per_user_per_day;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerWeek(): ?int
    {
        return $this->per_user_per_week;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerMonth(): ?int
    {
        return $this->per_user_per_month;
    }

    /**
     * @return int
     */
    public function getMinLevel(): int
    {
        return $this->min_level;
    }
}
