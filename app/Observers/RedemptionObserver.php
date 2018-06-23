<?php

namespace app\Observers;

use App\Models\NauModels\Redemption;

class RedemptionObserver
{
    /**
     * @param Redemption $redemption
     *
     */
    public function creating(Redemption $redemption)
    {
        $redemptionPointsPrice = $redemption->offer->getRedemptionPointsPriceAttribute();
        $referralPointsPrice   = $redemption->offer->getReferralPointsPriceAttribute();

        if ($redemptionPointsPrice > 0) {
            $redemption->user->withdrawRedemptionPoints($redemptionPointsPrice);
        }

        if ($referralPointsPrice > 0) {
            $redemption->user->withdrawReferralPoints($referralPointsPrice);
        }
    }

    /**
     * @param Redemption $redemption
     *
     */
    public function created(Redemption $redemption)
    {
        if ($redemption->offer->isWithoutPaymentInPoints()) {
            $redemption->user->enrollRedemptionPoints();
        }
    }
}
