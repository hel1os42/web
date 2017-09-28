<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\PictureRequest;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Response;
use Intervention\Image\ImageManager;

/**
 * Class AbstractPictureController
 * NS: App\Http\Controllers
 */
abstract class AbstractPictureController extends Controller
{
    protected $pictureWidth   = 192;
    protected $pictureHeight  = 192;
    protected $pictureQuality = 100;
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
    public function __construct(ImageManager $imageManager, Filesystem $filesystem)
    {
        $this->imageManager = $imageManager;
        $this->filesystem   = $filesystem;
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

        $picture = $this->imageManager
            ->make($request->file('picture'))
            ->fit($this->pictureWidth, $this->pictureHeight)
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
    protected function respondWithImageFor(string $identity): Response
    {
        $path = $this->picturePathFor($identity);

        return false === $this->filesystem->exists($path)
            ? \response()->error(Response::HTTP_NOT_FOUND)
            : \response($this->filesystem->get($path), Response::HTTP_OK)->header('Content-Type',
                $this->pictureMimeTypes[$this->pictureFormat]);
    }
}
