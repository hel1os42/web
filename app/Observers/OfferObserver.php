<?php

namespace App\Observers;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\CoreService;

class OfferObserver
{
    /**
     * @param Offer $offer
     */
    public function creating(Offer $offer)
    {
        $coreService = app()->make(CoreService::class);
        $coreService->offerCreated($offer)
            ->handle();
    }

    /**
     * @param Offer $offer
     */
    public function updating(Offer $offer)
    {
        $coreService = app()->make(CoreService::class);
        $coreService->offerUpdated($offer)
            ->handle();
    }
}
