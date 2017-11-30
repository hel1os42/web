<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PictureController
 * @package App\Http\Controllers\Profile
 */
class PictureController extends AbstractPictureController
{
    const PROFILE_PICTURES_PATH = 'images/profile/pictures';

    /**
     * Saves profile image from request
     *
     * @param PictureRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request)
    {
        $this->authorize('users.picture.store', $this->auth->user());

        return $this->storeImageFor($request, $this->auth->id(), route('profile.picture.show'));
    }

    /**
     * Retrieves and responds with profile image
     *
     * @param string|null $userUuid
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $userUuid = null): Response
    {
        $userUuid = $userUuid ?? $this->auth->id();
        if ($userUuid === null) {
            throw new NotFoundHttpException();
        }

        return $this->respondWithImageFor($userUuid);
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return self::PROFILE_PICTURES_PATH;
    }
}
