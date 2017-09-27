<?php

namespace App\Http\Controllers\Place;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Models\Place;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PictureController
 * @package App\Http\Controllers\Place
 */
class PictureController extends AbstractPictureController
{
    private const PLACE_PICTURES_PATH = 'images/place/pictures';
    private const PLACE_COVERS_PATH   = 'images/place/covers';

    private const TYPE_COVER   = 'cover';
    private const TYPE_PICTURE = 'picture';

    private $type = 'picture';
    private $auth;
    private $placeRepository;

    public function __construct(
        ImageManager $imageManager,
        Filesystem $filesystem,
        AuthManager $authManager,
        PlaceRepository $placeRepository
    ) {
        parent::__construct($imageManager, $filesystem);

        $this->auth            = $authManager->guard();
        $this->placeRepository = $placeRepository;
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
        $this->type = self::TYPE_PICTURE;

        return $this->store($request);
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
        $this->pictureHeight = 200;
        $this->pictureWidth  = 600;

        return $this->store($request);
    }

    private function store(PictureRequest $request)
    {
        $place = $this->placeRepository->findByUser($this->auth->user());

        if (null === $place) {
            throw (new ModelNotFoundException)->setModel(Place::class);
        }

        return $this->storeImageFor($request, $place->getId(),
            route('place.picture.show', ['uuid' => $place->getId(), 'type' => $this->type]));
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
