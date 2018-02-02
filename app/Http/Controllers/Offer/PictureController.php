<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Models\OfferData;
use App\Repositories\OfferRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Offer
 */
class PictureController extends AbstractPictureController
{
    const OFFER_PICTURES_PATH = 'images/offer/pictures';

    /**
     * @param PictureRequest  $request
     * @param string          $offerId
     * @param OfferRepository $offerRepository
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request, string $offerId, OfferRepository $offerRepository)
    {
        try {

            $offer = $offerRepository->findWithoutGlobalScopes($offerId);
            $this->authorize('offers.picture.store', $offer);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $modelNotFoundException) {

            $offerData = OfferData::findOrFail($offerId);
            $this->authorize('offers.picture.store.byOfferData', $offerData->owner);

        }

        $redirect = (request()->wantsJson())
            ? route('offer.picture.show', ['offerId' => $offerId])
            : route('advert.offers.index');

        return $this->storeImageFor($request, $offerId, $redirect);
    }

    /**
     * @param string          $offerId
     * @param OfferRepository $offerRepository
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $offerId, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->findWithoutGlobalScopes($offerId);

        return $this->respondWithImageFor($offer->id);
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return self::OFFER_PICTURES_PATH;
    }
}
