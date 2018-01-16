<?php

namespace App\Services\Implementation;

use App\Models\NauModels\Offer;
use App\Models\Place;
use App\Services\PlaceService as PlaceServiceImpl;

/**
 * Class PlaceService
 */
class PlaceService implements PlaceServiceImpl
{
    /**
     * @param Place $place
     * @param bool  $setUserApprovedFlag
     *
     * @return mixed|void
     */
    public function disapprove(Place $place, bool $setUserApprovedFlag = false)
    {
        if ($place->hasActiveOffers()) {
            $offers = new Offer();
            $offers = $offers->byOwner($place->user);
            /**
             * @var Offer $offer
             */
            foreach ($offers as $offer) {
                $offer->setStatus(Offer::STATUS_DEACTIVE)->save();
            }
        }

        if ($setUserApprovedFlag) {
            $place->user->setApproved(false)->save();
        }
    }
}
