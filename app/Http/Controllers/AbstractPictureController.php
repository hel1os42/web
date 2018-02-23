<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\PictureRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Response;
use Intervention\Image\ImageManager;

/**
 * Class AbstractPictureController
 * NS: App\Http\Controllers
 */
abstract class AbstractPictureController extends Controller
{
    const MOBILE_SIZE_TYPE  = 'mobile';
    const DESKTOP_SIZE_TYPE = 'desktop';

    protected $pictureObjectType = 'user';

    protected $pictureWidth   = 1024;
    protected $pictureHeight  = 1024;
    protected $pictureQuality = 80;
    protected $pictureFormat  = 'jpg';

    protected $pictureMimeTypes = [
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
    public function __construct(ImageManager $imageManager, Filesystem $filesystem, AuthManager $authManager)
    {
        $this->imageManager = $imageManager;
        $this->filesystem   = $filesystem;

        parent::__construct($authManager);
    }

    /**
     * @param PictureRequest $request
     * @param string         $identity
     * @param string         $redirect
     *
     * @return Response|\Illuminate\Routing\Redirector
     *
     * @throws \LogicException
     * @throws \RuntimeException
     */
    protected function storeImageFor(PictureRequest $request, string $identity, string $redirect)
    {
        $imagesPath = $this->picturePathFor($identity);

        $this->loadFormatSize('original');

        $picture = $this->imageManager
            ->make($request->file('picture'))
            ->resize($this->pictureWidth, $this->pictureHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($this->pictureFormat, $this->pictureQuality);

        $this->filesystem->put($imagesPath, $picture);

        return $request->wantsJson()
            ? \response()->render('', [], Response::HTTP_CREATED, $redirect)
            : \redirect($redirect);
    }

    /**
     * @param string $uuid
     *
     * @return string
     * @throws \RuntimeException
     */
    private function picturePathFor(string $uuid): string
    {
        $path   = $this->getPath();
        $exists = $this->filesystem->exists($path)
                  || $this->filesystem->makeDirectory($path);

        if (!$exists) {
            throw new \RuntimeException('Cannot create directory at: ' . $path);
        }

        return sprintf($path . '/%s.%s', $uuid, $this->pictureFormat);
    }

    abstract protected function getPath(): string;

    /**
     * @param string|null $identity
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    protected function respondWithImageFor(string $identity, string $sizeType = 'original'): Response
    {
        $this->loadFormatSize($sizeType);
        $path = $this->picturePathFor($identity);

        if ($this->filesystem->exists($path)) {
            $picture = $this->imageManager
                ->make(storage_path('app/' . $path))
                ->resize($this->pictureWidth, $this->pictureHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode();

            return \response($picture, Response::HTTP_OK)->header('Content-Type',
                $this->pictureMimeTypes[$this->pictureFormat]);
        }


        return \response()->error(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string $sizeType
     */
    protected function loadFormatSize(string $sizeType = 'original')
    {
        $imageTypesConfig = config('nau.image_types.' . $this->pictureObjectType);
        switch ($sizeType) {
            case self::DESKTOP_SIZE_TYPE || self::MOBILE_SIZE_TYPE:
                $pictureSizeType = $sizeType;
                break;
            default:
                $pictureSizeType = 'original';
        }
        $pictureFormatSize = $imageTypesConfig[$pictureSizeType];

        $this->pictureWidth  = $pictureFormatSize['width'];
        $this->pictureHeight = $pictureFormatSize['height'];
    }
}
