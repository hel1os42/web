<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Limits
 *
 * @property int offers
 * @property int perDay
 * @property int perUser
 * @property int minLevel
 */
class Limits implements \JsonSerializable
{
    /** @var int */
    private $offers;

    /** @var int */
    private $perDay;

    /** @var int */
    private $perUser;

    /** @var int */
    private $minLevel;

    /**
     * Limits constructor.
     * @param int $offers
     * @param int $perDay
     * @param int $perUser
     * @param int $minLevel
     */
    public function __construct(int $offers, int $perDay, int $perUser, int $minLevel)
    {
        $this->setOffers($offers)
            ->setPerDay($perDay)
            ->setPerUser($perUser)
            ->setMinLevel($minLevel);
    }

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
    public function getOffers() : int
    {
        return $this->offers;
    }

    /**
     * @return int
     */
    public function getPerDay() : int
    {
        return $this->perDay;
    }

    /**
     * @return int
     */
    public function getPerUser() : int
    {
        return $this->perUser;
    }

    /**
     * @return int
     */
    public function getMinLevel() : int
    {
        return $this->minLevel;
    }

    /**
     * @param int $offers
     * @return Limits
     */
    public function setOffers(int $offers) : Limits
    {
        $this->offers = $offers;
        return $this;
    }

    /**
     * @param int $perDay
     * @return Limits
     */
    public function setPerDay(int $perDay) : Limits
    {
        $this->perDay = $perDay;
        return $this;
    }

    /**
     * @param int $perUser
     * @return Limits
     */
    public function setPerUser(int $perUser) : Limits
    {
        $this->perUser = $perUser;
        return $this;
    }

    /**
     * @param int $minLevel
     * @return Limits
     */
    public function setMinLevel(int $minLevel) : Limits
    {
        $this->minLevel = $minLevel;
        return $this;
    }
}
