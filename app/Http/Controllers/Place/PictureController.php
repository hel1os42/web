<?php

namespace App\Http\Controllers\Place;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Models\Place;
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

    const TYPE_COVER   = 'cover';
    const TYPE_PICTURE = 'picture';

    protected $type = 'picture';

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
        /** @var Place $place */
        $place = Place::byUser(auth()->user());

        return $this->storeImageFor($request, $place->getId(),
            route('place.picture.show', ['uuid' => $place->getId(), 'type' => self::TYPE_PICTURE]));
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
        $this->type = self::TYPE_COVER;
        /** @var Place $place */
        $place = Place::byUser(auth()->user());

        $this->pictureHeight = 200;
        $this->pictureWidth  = 600;

        return $this->storeImageFor($request, $place->getId(),
            route('place.picture.show', ['uuid' => $place->getId(), 'type' => self::TYPE_COVER]));
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
        $this->type = isset($type) === false ? SELF::TYPE_PICTURE : $type;

        /** @var Place $place */
        $place = Place::findOrFail($placeId);

        return $this->respondWithImageFor($place->getId());
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
