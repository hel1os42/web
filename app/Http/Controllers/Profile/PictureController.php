<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PictureRequest;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Profile
 */
class PictureController extends Controller
{
    private const PROFILE_PICTURES_PATH = 'images/profile/pictures';
    private const PICTURE_WIDTH = 192;
    private const PICTURE_HEIGHT = 192;
    private const PICTURE_QUALITY = 100;
    private const PICTURE_FORMAT = 'jpg';

    private const PICTURE_MIMETYPES = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
    ];

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
     *
     * @param ImageManager $imageManager
     * @param Filesystem   $filesystem
     */
    public function __construct(ImageManager $imageManager, Filesystem $filesystem)
    {
        $this->imageManager = $imageManager;
        $this->filesystem   = $filesystem;
    }


    /**
     * @param PictureRequest $request
     *
     * @return \Illuminate\Routing\Redirector|Response
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function store(PictureRequest $request)
    {
        $imagesPath = $this->picturePathFor(auth()->id());

        $picture = $this->imageManager
            ->make($request->file('picture'))
            ->fit(self::PICTURE_WIDTH, self::PICTURE_HEIGHT)
            ->encode(self::PICTURE_FORMAT, self::PICTURE_QUALITY);

        $this->filesystem->put($imagesPath, $picture);

        return $request->wantsJson()
            ? \response()->render('', [], Response::HTTP_CREATED, route('profile.picture.show'))
            : \redirect(route('profile.picture.show'));
    }

    /**
     * @param string|null $userUuid
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $userUuid = null): Response
    {
        if (is_null($userUuid)) {
            $userUuid = \auth()->id();
        }

        $path = $this->picturePathFor($userUuid);

        return false === $this->filesystem->exists($path)
            ? \response()->error(Response::HTTP_NOT_FOUND)
            : \response($this->filesystem->get($path), Response::HTTP_OK)->header('Content-Type',
                self::PICTURE_MIMETYPES[self::PICTURE_FORMAT]);
    }

    /**
     * @param string $uuid
     *
     * @return string
     * @throws \RuntimeException
     */
    private function picturePathFor(string $uuid): string
    {
        $exists = $this->filesystem->exists(self::PROFILE_PICTURES_PATH)
                  || $this->filesystem->makeDirectory(self::PROFILE_PICTURES_PATH);

        if (!$exists) {
            throw new \RuntimeException('Cannot create directory at: ' . self::PROFILE_PICTURES_PATH);
        }

        return sprintf(self::PROFILE_PICTURES_PATH . '/%s.%s', $uuid, self::PICTURE_FORMAT);
    }
}
