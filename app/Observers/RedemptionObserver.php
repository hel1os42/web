<?php

namespace App\Observers;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\CoreService;

class RedemptionObserver
{
    /**
     * @param Redemption $redemption
     */
    public function creating(Redemption $redemption)
    {
        $coreService = app()->make(CoreService::class);
        $coreService->offerRedemption($redemption)
            ->handle();
    }
}
