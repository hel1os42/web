<?php

namespace app\Observers;

use App\Models\NauModels\Offer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class OfferObserver
{
    public function deleting(Offer $offer)
    {
        if ('active' === $offer->status) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, trans('errors.offer_unprocessable_entity'));
        }
    }
}
