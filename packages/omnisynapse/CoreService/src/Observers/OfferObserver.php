<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Offer;

class OfferObserver extends AbstractJobObserver
{
    /**
     * @param Offer $offer
     *
     * @return bool
     * @throws \OmniSynapse\CoreService\Exception\RequestException
     */
    public function creating(Offer $offer)
    {
        return $this->queue($this->getCoreService()->offerCreated($offer));
    }

    /**
     * @param Offer $offer
     *
     * @return bool
     * @throws \OmniSynapse\CoreService\Exception\RequestException
     */
    public function updating(Offer $offer)
    {
        return $this->queue($this->getCoreService()->offerUpdated($offer));
    }
}
