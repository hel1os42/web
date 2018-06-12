<?php

namespace App\Services\OfferRedemption\Access\Rules;

class MinUserReferralPoints extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MIN_REFERRAL_POINTS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->customer->getReferralPoints() >= $this->limit;
    }
}
