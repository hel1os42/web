<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\CoreService;

class OfferObserver
{
    /**
     * @param Offer $offer
     */
    public function created(Offer $offer)
    {
        $coreService = app()->make(CoreService::class);
        dispatch($coreService->offerCreated($offer));
    }

    /**
     * @param Offer $offer
     */
    public function updated(Offer $offer)
    {
        $coreService = app()->make(CoreService::class);
        dispatch($coreService->offerUpdated($offer));
    }
}
