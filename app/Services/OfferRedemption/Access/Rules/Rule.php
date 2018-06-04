<?php

namespace App\Services\OfferRedemption\Access\Rules;

use App\Models\NauModels\Offer;
use App\Models\User;

abstract class Rule
{
    public const LIMIT_MAX_OFFER_TOTAL_REDEMPTIONS  = 1 << 0;
    public const LIMIT_MAX_OFFER_DAILY_REDEMPTIONS  = 1 << 1;
    public const LIMIT_MAX_USER_TOTAL_REDEMPTIONS   = 1 << 2;
    public const LIMIT_MAX_USER_DAILY_REDEMPTIONS   = 1 << 3;
    public const LIMIT_MAX_USER_WEEKLY_REDEMPTIONS  = 1 << 4;
    public const LIMIT_MAX_USER_MONTHLY_REDEMPTIONS = 1 << 5;
    public const LIMIT_MIN_USER_LEVEL               = 1 << 6;

    /**
     * @var Offer
     */
    public $offer;

    /**
     * @var User
     */
    public $customer;

    /**
     * @var int
     */
    public $limit;

    /**
     * Rule constructor.
     *
     * @param Offer $offer
     * @param User $customer
     * @param int $limit
     */
    public function __construct(Offer $offer, User $customer, int $limit)
    {
        $this->offer    = $offer;
        $this->customer = $customer;
        $this->limit    = $limit;
    }

    /**
     * @return int
     */
    abstract public function getErrorCode(): int;

    /**
     * @return bool
     */
    abstract public function validate(): bool;
}
