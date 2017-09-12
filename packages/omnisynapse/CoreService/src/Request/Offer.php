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
    /** @var string */
    public $ownerId;

    /** @var string */
    public $name;

    /** @var string */
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

    /** @var Carbon */
    public $endDate;

    /** @var Carbon */
    public $startTime;

    /** @var Carbon */
    public $endTime;

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
        $limits  = new Limits($offer->getMaxCount(), $offer->getMaxPerDay(), $offer->getMaxForUser(), $offer->getUserLevelMin());
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
            ->setEndDate($offer->getFinishDate())
            ->setStartTime($offer->getStartTime())
            ->setEndTime($offer->getFinishTime());
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
            'start_date'        => $this->startDate,
            'end_date'          => $this->endDate,
            'start_time'        => $this->startTime,
            'end_time'          => $this->endTime,
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
     * @param string $description
     * @return Offer
     */
    public function setDescription(string $description): Offer
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
        $this->startDate = $startDate->toDateString();
        return $this;
    }

    /**
     * @param Carbon $endDate
     * @return Offer
     */
    public function setEndDate(Carbon $endDate): Offer
    {
        $this->endDate = $endDate->toDateString();
        return $this;
    }

    /**
     * @param Carbon $startTime
     * @return Offer
     */
    public function setStartTime(Carbon $startTime): Offer
    {
        $this->startTime = $startTime->toTimeString();
        return $this;
    }

    /**
     * @param Carbon $endTime
     * @return Offer
     */
    public function setEndTime(Carbon $endTime): Offer
    {
        $this->endTime = $endTime->toTimeString();
        return $this;
    }
}
