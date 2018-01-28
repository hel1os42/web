<?php

namespace App\Http\Controllers\Place;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Repositories\PlaceRepository;
use App\Services\PlaceService;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PictureController
 * @package App\Http\Controllers\Place
 */
class PictureController extends AbstractPictureController
{
    const PLACE_PICTURES_PATH = 'images/place/pictures';
    const PLACE_COVERS_PATH   = 'images/place/covers';

    const TYPE_COVER   = 'cover';
    const TYPE_PICTURE = 'picture';

    private $type = 'picture';
    private $placeRepository;
    /**
     * @var $placeService PlaceService
     */
    private $placeService;

    public function __construct(
        ImageManager $imageManager,
        Filesystem $filesystem,
        AuthManager $authManager,
        PlaceRepository $placeRepository,
        PlaceService $placeService
    ) {
        parent::__construct($imageManager, $filesystem, $authManager);

        $this->placeRepository = $placeRepository;
        $this->placeService    = $placeService;
    }

    /**
     * Saves place image from request
     *
     * @param PictureRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function storePicture(PictureRequest $request)
    {
        $place = $this->placeRepository->findByUser($this->user());

        $this->authorize('places.picture.store', $place);

        $imageService = app()->makeWith('App\Services\ImageService', [
            'file' => $request->file('picture')
        ]);

        $imageService->savePlacePicture($place);

        // very strange redirect condition copy/pasted from another place
        $redirect = !$request->wantsJson() && $this->auth->user()->isAdvertiser()
            ? route('profile.place.show')
            : route('profile.picture.show');

        return $request->wantsJson()
            ? response()->render('', [], Response::HTTP_CREATED, $redirect)
            : redirect($redirect);
    }

    /**
     * Saves place cover from request
     *
     * @param PictureRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function storeCover(PictureRequest $request)
    {
        $this->type          = self::TYPE_COVER;
        $this->pictureHeight = 400;
        $this->pictureWidth  = 1200;

        return $this->store($request);
    }

    private function store(PictureRequest $request)
    {
        $place = $this->placeRepository->findByUser($this->user());

        $this->authorize('places.picture.store', $place);

        if (!$this->user()->isAgent() && !$this->user()->isAdmin()) {
            $this->placeService->disapprove($place, true);
        }

        $redirect = (!$request->wantsJson() && $this->user()->isAdvertiser()) ? route('profile.place.show') : route('profile.picture.show');

        return $this->storeImageFor($request, $place->getId(), $redirect);
    }

    /**
     * Retrieves and responds with place image
     *
     * @param string $placeId
     * @param string $type
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $placeId, string $type): Response
    {
        $this->type = $type;
        $place      = $this->placeRepository->find($placeId);

        return $this->respondWithImageFor($place->id);
    }

    protected function getPath(): string
    {
        switch ($this->type) {
            case self::TYPE_COVER:
                return self::PLACE_COVERS_PATH;
            case self::TYPE_PICTURE:
                return self::PLACE_PICTURES_PATH;
            default:
                throw new NotFoundHttpException();
        }
    }
}
