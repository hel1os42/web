<?php

namespace App\Services\Implementation;

use App\Models\Place;
use App\Services\ImageService as ImageServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Image;

class ImageService implements ImageServiceInterface
{

    /**
     * @var Image
     */
    protected $image;

    /**
     * ImageService constructor.
     *
     * @param UploadedFile $file
     * @param ImageManager $imageManager
     * @param Filesystem $fileSystem
     */
    public function __construct(UploadedFile $file, ImageManager $imageManager, Filesystem $fileSystem)
    {
        $this->imageManager = $imageManager;
        $this->fileSystem   = $fileSystem;

        $this->image = $this->imageManager->make($file);
    }

    /**
     * @param int $width
     * @param int|null $height
     * @return $this
     */
    public function fit(int $width, int $height = null)
    {
        $this->image->fit($width, $height);

        return $this;
    }

    /**
     * @param int|null $width
     * @param int|null $height
     * @return $this
     */
    public function resize(int $width = null, int $height = null)
    {
        if ($width && $this->image->getWidth() > $width) {
            $this->image->widen($width);
        }

        if ($height && $this->image->getHeight() > $height) {
            $this->image->heighten($height);
        }

        return $this;
    }

    /**
     * @param string $format
     * @param int $quality
     * @return $this
     */
    public function encode(string $format = self::DEFAULT_ENCODING_FORMAT, int $quality = self::DEFAULT_QUALITY)
    {
        $this->image->encode($format, $quality);

        return $this;
    }

    /**
     * @param string $destination
     * @return bool
     */
    public function save(string $destination): bool
    {
        $dirName = pathinfo($destination, PATHINFO_DIRNAME);

        $exists = $this->fileSystem->exists($dirName) || $this->fileSystem->makeDirectory($dirName);

        if (!$exists) {
            throw new \RuntimeException('Cannot create directory ' . $dirName);
        }

        return $this->fileSystem->put($destination, $this->image);
    }

    /**
     * @param Place $place
     * @return string
     */
    public function savePlacePicture(Place $place): string
    {
        $destination = sprintf('%1$s/%2$s.%3$s', self::PLACE_PICTURES_PATH, $place->getId(), self::DEFAULT_ENCODING_FORMAT);

        $isStored = $this->resize(self::MAX_PLACE_PICTURE_WIDTH, self::MAX_PLACE_PICTURE_HEIGHT)
            ->encode(self::DEFAULT_ENCODING_FORMAT)
            ->save($destination);

        return $isStored ? $destination : '';
    }
}