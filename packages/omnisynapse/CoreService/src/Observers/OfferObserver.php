<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Offer;

class OfferObserver extends AbstractJobObserver
{
    /**
     * @param Offer $offer
     */
    public function creating(Offer $offer)
    {
        return $this->queue($this->getCoreService()->offerCreated($offer));
    }

    /**
     * @param Offer $offer
     */
    public function updating(Offer $offer)
    {
        return $this->queue($this->getCoreService()->offerUpdated($offer));
    }
}
