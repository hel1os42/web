<?php

namespace app\Observers;

use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferObserver
{
    /**
     * @param Offer $offer
     *
     * @throws HttpException
     */
    public function deleting(Offer $offer)
    {
        if ('active' === $offer->status) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.offer_unprocessable_entity'));
        }
    }

    /**
     * @param Offer $offer
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function created(Offer $offer)
    {
        $this->syncPlaceActiveOffersCount($offer);
    }

    /**
     * @param Offer $offer
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function updated(Offer $offer)
    {
        $this->syncPlaceActiveOffersCount($offer);
    }

    /**
     * @param Offer $offer
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function deleted(Offer $offer)
    {
        $this->syncPlaceActiveOffersCount($offer);
    }

    /**
     * @param Offer $offer
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected function syncPlaceActiveOffersCount(Offer $offer)
    {
        $place = $offer->account->owner->place;

        $activeOffersInPlaceStatus = $place->active_offers_count > 0;

        if ($activeOffersInPlaceStatus !== $place->hasActiveOffers()) {
            $place->setHasActiveOffers($activeOffersInPlaceStatus)->update();
        }
    }
}
