<?php

namespace App\Services\OfferRedemption\Access\Rules;

class MaxTotalOfferRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_OFFER_TOTAL_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}