<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Limits
 *
 * @property int offers
 * @property int per_day
 * @property int per_user
 * @property int min_level
 */
class Limits implements \JsonSerializable
{
    /** @var int */
    private $offers;

    /** @var int */
    private $per_day;

    /** @var int */
    private $per_user;

    /** @var int */
    private $min_level;

    /**
     * Limits constructor.
     * @param int $offers
     * @param int $per_day
     * @param int $per_user
     * @param int $min_level
     */
    public function __construct(int $offers, int $per_day, int $per_user, int $min_level)
    {
        $this->setOffers($offers)
            ->setPerDay($per_day)
            ->setPerUser($per_user)
            ->setMinLevel($min_level);
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
        return $this->per_day;
    }

    /**
     * @return int
     */
    public function getPerUser() : int
    {
        return $this->per_user;
    }

    /**
     * @return int
     */
    public function getMinLevel() : int
    {
        return $this->min_level;
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
     * @param int $per_day
     * @return Limits
     */
    public function setPerDay(int $per_day) : Limits
    {
        $this->per_day = $per_day;
        return $this;
    }

    /**
     * @param int $per_user
     * @return Limits
     */
    public function setPerUser(int $per_user) : Limits
    {
        $this->per_user = $per_user;
        return $this;
    }

    /**
     * @param int $min_level
     * @return Limits
     */
    public function setMinLevel(int $min_level) : Limits
    {
        $this->min_level = $min_level;
        return $this;
    }
}