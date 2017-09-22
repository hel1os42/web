<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PictureRequest;
use Intervention\Image\ImageManager;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Profile
 */
class PictureController extends Controller
{
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * PictureController constructor.
     * @param ImageManager $imageManager
     * @param Filesystem $filesystem
     */
    public function __construct(ImageManager $imageManager, Filesystem $filesystem)
    {
        $this->imageManager = $imageManager;
        $this->filesystem   = $filesystem;
    }


    /**
     * @param PictureRequest $request
     * @return Response|\Illuminate\Routing\Redirector
     */
    public function store(PictureRequest $request)
    {
        $path = storage_path(sprintf('app/img/profile/pictures/%s.jpg', auth()->id()));
        $this->imageManager->make($request->file('picture'))->fit('192', '192')->encode('jpg',
            80)->save($path . '548484');

        return $request->wantsJson() ?
            \response()->render('', [], Response::HTTP_CREATED, route('profile.picture.show')) :
            \redirect(route('profile.picture.show'));
    }

    /**
     * @param string|null $userUuid
     * @return Response
     */
    public function show(string $userUuid = null): Response
    {
        if (is_null($userUuid)) {
            $userUuid = \auth()->id();
        }

        $path = sprintf('img/profile/pictures/%s.jpg', $userUuid);

        return false === $this->filesystem->exists($path) ?
            \response()->error(Response::HTTP_NOT_FOUND) :
            \response($this->filesystem->get($path), 200)->header('Content-Type', 'image/jpeg');
    }
}
