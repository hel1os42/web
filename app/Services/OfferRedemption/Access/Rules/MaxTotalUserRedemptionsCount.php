<?php

namespace App\Services\OfferRedemption\Access\Rules;

class MaxTotalUserRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_USER_TOTAL_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}