<?php

namespace App\Services\OfferRedemption\Access;

interface Moderator
{
    public const RESTRICTION_TOTAL_LIMIT_FOR_OFFER  = 'total_limit_for_offer';
    public const RESTRICTION_DAILY_LIMIT_FOR_OFFER  = 'daily_limit_for_offer';
    public const RESTRICTION_TOTAL_LIMIT_FOR_USER   = 'total_limit_for_user';
    public const RESTRICTION_DAILY_LIMIT_FOR_USER   = 'daily_limit_for_user';
    public const RESTRICTION_WEEKLY_LIMIT_FOR_USER  = 'weekly_limit_for_user';
    public const RESTRICTION_MONTHLY_LIMIT_FOR_USER = 'monthly_limit_for_user';
    public const RESTRICTION_MIN_USER_LEVEL         = 'min_user_level';

    /**
     * @return int
     */
    public function getAccessCode(): int;

    /**
     * @return array
     */
    public function getRestrictions(): array;
}