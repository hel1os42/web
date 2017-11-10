<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Offer
 */
class PictureController extends AbstractPictureController
{
    const OFFER_PICTURES_PATH = 'images/offer/pictures';
    private $offerRepository;

    public function __construct(
        ImageManager $imageManager,
        Filesystem $filesystem,
        AuthManager $authManager,
        OfferRepository $offerRepository
    ) {
        parent::__construct($imageManager, $filesystem, $authManager);

        $this->offerRepository = $offerRepository;
    }

    /**
     * @param PictureRequest $request
     * @param string         $offerId
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request, string $offerId)
    {

        $offer = $this->offerRepository->find($offerId);

        $this->authorize('pictureStore', $offer);

        return $this->storeImageFor($request, $offer->id, route('offer.picture.show', ['offerId' => $offer->id]));
    }

    /**
     * Retrieves and responds with offer image
     *
     * @param string $offerId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $offerId): Response
    {
        $offer = $this->offerRepository->find($offerId);

        $this->authorize('pictureShow', $offer);

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
