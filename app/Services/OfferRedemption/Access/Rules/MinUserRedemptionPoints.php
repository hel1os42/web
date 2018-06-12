<?php

namespace App\Services\OfferRedemption\Access\Rules;

class MinUserRedemptionPoints extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MIN_REDEMPTION_POINTS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->customer->getRedemptionPoints() >= $this->limit;
    }
}
