<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Profile
 */
class PictureController extends AbstractPictureController
{
    private const PROFILE_PICTURES_PATH = 'images/profile/pictures';

    /**
     * Saves profile image from request
     *
     * @param PictureRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request)
    {
        return $this->storeImageFor($request, auth()->id(), route('profile.picture.show'));
    }

    /**
     * Retrieves and responds with profile image
     *
     * @param string|null $userUuid
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $userUuid = null): Response
    {
        return $this->respondWithImageFor($userUuid);
    }

    protected function getPath(): string
    {
        return self::PROFILE_PICTURES_PATH;
    }
}
