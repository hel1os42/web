<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\OfferForRedemption;

class RedemptionObserver
{
    /**
     * @param Redemption $redemption
     */
    public function created(Redemption $redemption)
    {
        $coreService = app()->make(CoreService::class);

        \Event::listen(OfferForRedemption::class, function ($response) use($redemption) {
            $redemption['id'] = $response->getId();
        });

        $coreService->offerRedemption($redemption)
            ->handle();
    }
}
