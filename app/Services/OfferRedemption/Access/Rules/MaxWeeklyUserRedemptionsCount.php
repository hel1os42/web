<?php

namespace App\Services\OfferRedemption\Access\Rules;

use Carbon\Carbon;

class MaxWeeklyUserRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_USER_WEEKLY_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $userWeeklyRedemptionsCount = $this->offer->redemptions()
            ->weekly(Carbon::now(config('app.timezone')))
            ->byUser($this->customer)
            ->count();

        return $userWeeklyRedemptionsCount < $this->limit;
    }
}
