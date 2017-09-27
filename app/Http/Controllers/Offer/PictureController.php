<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Models\NauModels\Offer;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Offer
 */
class PictureController extends AbstractPictureController
{
    private const OFFER_PICTURES_PATH = 'images/offer/pictures';
    private $offerRepository;
    private $auth;

    public function __construct(
        ImageManager $imageManager,
        Filesystem $filesystem,
        AuthManager $authManager,
        OfferRepository $offerRepository
    ) {
        parent::__construct($imageManager, $filesystem);

        $this->auth            = $authManager->guard();
        $this->offerRepository = $offerRepository;
    }

    /**
     * Saves offer image from request
     *
     * @param PictureRequest $request
     * @param string         $offerId
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request, string $offerId)
    {
        $offer = $this->offerRepository->findByIdAndOwner($offerId, $this->auth->user());

        if (null === $offer) {
            throw (new ModelNotFoundException)->setModel(Offer::class);
        }

        return $this->storeImageFor($request, $offer->id, route('offer.picture.show', ['offerId' => $offer->id]));
    }

    /**
     * Retrieves and responds with offer image
     *
     * @param string $offerId
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $offerId): Response
    {
        $offer = $this->offerRepository->find($offerId);

        return $this->respondWithImageFor($offer->id);
    }

    protected function getPath(): string
    {
        return self::OFFER_PICTURES_PATH;
    }
}
