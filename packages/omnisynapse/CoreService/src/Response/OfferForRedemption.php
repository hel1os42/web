<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class OfferForRedemption.
 * @package OmniSynapse\CoreService\Response
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OfferForRedemption
{
    /** @var string */
    public $id;

    /** @var string */
    public $offer_id;

    /** @var string */
    public $user_id;

    /** @var int */
    public $points;

    /** @var string */
    public $rewarded_id;

    /** @var float */
    public $amount;

    /** @var float */
    public $fee;

    /** @var string */
    public $created_at;

    /** @var Transaction|null */
    public $transaction;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOfferId(): string
    {
        return $this->offer_id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return float
     */
    public function getPoints(): float
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getRewardedId(): string
    {
        return $this->rewarded_id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getFee(): float
    {
        return $this->fee;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }
}
