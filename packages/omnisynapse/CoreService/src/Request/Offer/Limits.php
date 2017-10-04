<?php

namespace OmniSynapse\CoreService\Request\Offer;

/**
 * Class Limits
 * @package OmniSynapse\CoreService\Request\Offer
 *
 * @property int|null offers
 * @property int|null perDay
 * @property int|null perUser
 * @property int|null minLevel
 */
class Limits implements \JsonSerializable
{
    /** @var int|null */
    private $offers;

    /** @var int|null */
    private $perDay;

    /** @var int|null */
    private $perUser;

    /** @var int|null */
    private $minLevel;

    /**
     * Limits constructor.
     *
     * @param int|null $offers
     * @param int|null $perDay
     * @param int|null $perUser
     * @param int      $minLevel
     */
    public function __construct(?int $offers, ?int $perDay, ?int $perUser, int $minLevel)
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
    public function setOffers(?int $offers): Limits
    {
        $this->offers = $offers;
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
     * @param int $minLevel
     * @return Limits
     */
    public function setMinLevel(int $minLevel): Limits
    {
        $this->minLevel = $minLevel;
        return $this;
    }
}
