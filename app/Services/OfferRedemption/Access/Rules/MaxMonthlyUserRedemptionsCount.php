<?php

namespace App\Services\OfferRedemption\Access\Rules;

use Carbon\Carbon;

class MaxMonthlyUserRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_USER_MONTHLY_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $userMonthlyRedemptionsCount = $this->offer->redemptions()
            ->monthly(Carbon::now(config('app.timezone')))
            ->byUser($this->customer)
            ->count();

        return $userMonthlyRedemptionsCount < $this->limit;
    }
}
