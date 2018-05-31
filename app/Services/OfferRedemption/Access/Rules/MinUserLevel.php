<?php

namespace App\Services\OfferRedemption\Access\Rules;

class MinUserLevel extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MIN_USER_LEVEL;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->customer->getLevel() >= $this->limit;
    }
}
