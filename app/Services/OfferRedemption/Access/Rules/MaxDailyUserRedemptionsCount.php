<?php

namespace App\Services\OfferRedemption\Access\Rules;

use Carbon\Carbon;

class MaxDailyUserRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_USER_DAILY_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $userDailyRedemptionsCount = $this->offer->redemptions()
            ->daily(Carbon::now(config('app.timezone')))
            ->byUser($this->customer)
            ->count();

        return $userDailyRedemptionsCount < $this->limit;
    }
}
