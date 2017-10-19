<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property int|null maxCount
 * @property int|null perDay
 * @property int|null perUser
 * @property int      minLevel
 * @property int|null perUserPerDay
 * @property int|null perUserPerWeek
 * @property int|null perUserPerMonth
 */
class Limits implements \JsonSerializable
{
    /** @var int|null */
    private $maxCount;

    /** @var int|null */
    private $perDay;

    /** @var int|null */
    private $perUser;

    /**
     * @var int|null
     */
    private $perUserPerDay;

    /**
     * @var int|null
     */
    private $perUserPerWeek;

    /**
     * @var int|null
     */
    private $perUserPerMonth;

    /** @var int */
    private $minLevel;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'max_count'          => $this->getMaxCount(),
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
    public function getMaxCount(): ?int
    {
        return $this->maxCount;
    }

    /**
     * @return int|null
     */
    public function getPerDay(): ?int
    {
        return $this->perDay;
    }

    /**
     * @return int|null
     */
    public function getPerUser(): ?int
    {
        return $this->perUser;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerDay(): ?int
    {
        return $this->perUserPerDay;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerWeek(): ?int
    {
        return $this->perUserPerWeek;
    }

    /**
     * @return int|null
     */
    public function getPerUserPerMonth(): ?int
    {
        return $this->perUserPerMonth;
    }

    /**
     * @return int
     */
    public function getMinLevel(): int
    {
        return $this->minLevel;
    }

    /**
     * @param int|null $offers
     *
     * @return Limits
     */
    public function setMaxCount(?int $maxCount): Limits
    {
        $this->maxCount = $maxCount;
        return $this;
    }

    /**
     * @param int|null $perDay
     *
     * @return Limits
     */
    public function setPerDay(?int $perDay): Limits
    {
        $this->perDay = $perDay;
        return $this;
    }

    /**
     * @param int|null $perUser
     *
     * @return Limits
     */
    public function setPerUser(?int $perUser): Limits
    {
        $this->perUser = $perUser;
        return $this;
    }

    /**
     * @param int|null $perUserPerDay
     *
     * @return Limits
     */
    public function setPerUserPerDay(?int $perUserPerDay): Limits
    {
        $this->perUserPerDay = $perUserPerDay;

        return $this;
    }

    /**
     * @param int|null $perUserPerWeek
     *
     * @return Limits
     */
    public function setPerUserPerWeek(?int $perUserPerWeek): Limits
    {
        $this->perUserPerWeek = $perUserPerWeek;

        return $this;
    }

    /**
     * @param $perUserPerMonth
     *
     * @return Limits
     */
    public function setPerUserPerMonth($perUserPerMonth): Limits
    {
        $this->perUserPerMonth = $perUserPerMonth;

        return $this;
    }

    /**
     * @param int $minLevel
     * @return Limits
     */
    public function setMinLevel(int $minLevel): Limits
    {
        $this->minLevel = $minLevel;
        return $this;
    }
}
