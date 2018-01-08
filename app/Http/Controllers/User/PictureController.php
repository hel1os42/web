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
     * @param string|null    $userUuid
     * @param PictureRequest $request
     * @param UserRepository $userRepository
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(string $userUuid = null, PictureRequest $request, UserRepository $userRepository)
    {
        $userUuid = $this->getUserUuid($userUuid);

        $editableUser = $userRepository->find($userUuid);

        $this->authorize('users.picture.store', $editableUser);

        $redirect = ($request->wantsJson()) ? route('profile.picture.show') : route('profile');

        return $this->storeImageFor($request, $editableUser->getId(), $redirect);
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
        $userUuid = $userUuid ?? $this->guard->id();

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
