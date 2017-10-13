<?php

namespace OmniSynapse\CoreService\Request;

use Carbon\Carbon;
use OmniSynapse\CoreService\Exception\Exception;
use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Limits;
use OmniSynapse\CoreService\Request\Offer\Point;

/**
 * Class Offer
 * @package OmniSynapse\CoreService\Request
 */
class Offer implements \JsonSerializable
{
    const DATE_FORMAT = 'Y-m-d\TH:i:sO';
    const TIME_FORMAT = 'H:i:sO';

    /** @var string */
    public $ownerId;

    /** @var string */
    public $name;

    /** @var string|null */
    public $description;

    /** @var string */
    public $categoryId;

    /** @var Geo */
    public $geo;

    /** @var Limits */
    public $limits;

    /** @var float */
    public $reward;

    /** @var Carbon */
    public $startDate;

    /** @var Carbon|null */
    public $endDate;

    /**
     * Offer constructor.
     *
     * @param \App\Models\NauModels\Offer $offer
     */
    public function __construct(\App\Models\NauModels\Offer $offer)
    {
        $point = null;

        $lat = $offer->getLatitude();
        $lon = $offer->getLongitude();

        if (null !== $lat && null !== $lon) {
            $point = new Point($lat, $lon);
        }
        $geo     = new Geo($point, $offer->getRadius(), $offer->getCity(), $offer->getCountry());
        $limits  = (new Limits)
            ->setOffers($offer->getMaxCount())
            ->setPerDay($offer->getMaxPerDay())
            ->setPerUser($offer->getMaxForUser())
            ->setPerUserPerDay($offer->getMaxForUserPerDay())
            ->setPerUserPerWeek($offer->getMaxForUserPerWeek())
            ->setPerUserPerMonth($offer->getMaxForUserPerMonth())
            ->setMinLevel($offer->getUserLevelMin());
        $account = $offer->getAccount();

        if (null === $account) {
            throw new Exception('Offer do not have relation with account.');
        }

        $this->setOwnerId($account->getOwnerId())
            ->setName($offer->getLabel())
            ->setDescription($offer->getDescription())
            ->setCategoryId($offer->getCategoryId())
            ->setGeo($geo)
            ->setLimits($limits)
            ->setReward($offer->getReward())
            ->setStartDate($offer->getStartDate())
            ->setEndDate($offer->getFinishDate());
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'owner_id'          => $this->ownerId,
            'name'              => $this->name,
            'description'       => $this->description,
            'category_id'       => $this->categoryId,
            'geo'               => $this->geo->jsonSerialize(),
            'limits'            => $this->limits->jsonSerialize(),
            'reward'            => $this->reward,
            'start_date'        => $this->startDate->format(self::DATE_FORMAT),
            'end_date'          => $this->endDate->format(self::DATE_FORMAT),
        ];
    }

    /**
     * @param string $ownerId
     * @return Offer
     */
    public function setOwnerId(string $ownerId): Offer
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @param string $name
     * @return Offer
     */
    public function setName(string $name): Offer
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param null|string $description
     *
     * @return Offer
     */
    public function setDescription(?string $description): Offer
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $categoryId
     * @return Offer
     */
    public function setCategoryId(string $categoryId): Offer
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @param Geo $geo
     * @return Offer
     */
    public function setGeo(Geo $geo): Offer
    {
        $this->geo = $geo;
        return $this;
    }

    /**
     * @param Limits $limits
     * @return Offer
     */
    public function setLimits(Limits $limits): Offer
    {
        $this->limits = $limits;
        return $this;
    }

    /**
     * @param float $reward
     * @return Offer
     */
    public function setReward(float $reward): Offer
    {
        $this->reward = $reward;
        return $this;
    }

    /**
     * @param Carbon $startDate
     * @return Offer
     */
    public function setStartDate(Carbon $startDate): Offer
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param Carbon|null $endDate
     *
     * @return Offer
     */
    public function setEndDate(?Carbon $endDate): Offer
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEndDate(): ?string
    {
        return $this->endDate === null ? null : $this->endDate->format(self::DATE_FORMAT);
    }
}
