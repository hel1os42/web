<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property int      offers
 * @property int      perDay
 * @property int      perUser
 * @property int|null perUserPerDay
 * @property int|null perUserPerWeek
 * @property int|null perUserPerMonth
 * @property int      minLevel
 */
class Limits implements \JsonSerializable
{
    /** @var int */
    private $offers;

    /** @var int */
    private $perDay;

    /** @var int */
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
     * Limits constructor.
     *
     * @param int $offers
     * @param int $perDay
     * @param int $perUser
     * @param int|null $perUserPerDay
     * @param int|null $perUserPerWeek
     * @param int|null $perUserPerMonth
     * @param int $minLevel
     */
    public function __construct(
        int $offers,
        int $perDay,
        int $perUser,
        ?int $perUserPerDay,
        ?int $perUserPerWeek,
        ?int $perUserPerMonth,
        int $minLevel
    ) {
        $this->setOffers($offers)
             ->setPerDay($perDay)
             ->setPerUser($perUser)
             ->setPerUserPerDay($perUserPerDay)
             ->setPerUserPerWeek($perUserPerWeek)
             ->setPerUserPerMonth($perUserPerMonth)
             ->setMinLevel($minLevel);
    }

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
        return $this->perDay;
    }

    /**
     * @return int
     */
    public function getPerUser(): int
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
     * @param int $offers
     * @return Limits
     */
    public function setOffers(int $offers): Limits
    {
        $this->offers = $offers;
        return $this;
    }

    /**
     * @param int $perDay
     * @return Limits
     */
    public function setPerDay(int $perDay): Limits
    {
        $this->perDay = $perDay;
        return $this;
    }

    /**
     * @param int $perUser
     * @return Limits
     */
    public function setPerUser(int $perUser): Limits
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
