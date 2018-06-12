<?php

namespace App\Services\OfferRedemption\Access\Rules;

use Carbon\Carbon;

class MaxDailyOfferRedemptionsCount extends Rule
{

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return self::LIMIT_MAX_OFFER_DAILY_REDEMPTIONS;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $dailyRedemptionsCount = $this->offer->redemptions()
            ->daily(Carbon::now(config('app.timezone')))
            ->count();

        return $dailyRedemptionsCount < $this->limit;
    }
}
