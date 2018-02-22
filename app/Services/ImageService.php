<?php

namespace App\Services;

use App\Models\Place;

interface ImageService
{
    public const DEFAULT_ENCODING_FORMAT = 'png';
    public const DEFAULT_QUALITY         = 80;

    public const MAX_PLACE_PICTURE_WIDTH  = 1024;
    public const MAX_PLACE_PICTURE_HEIGHT = 1024;

    const PLACE_PICTURES_PATH = 'images/place/pictures';
    const PLACE_COVERS_PATH   = 'images/place/covers';

    public function fit(int $width, int $height = null);

    public function resize(int $width = null, int $height = null);

    public function encode(string $format, int $quality);

    public function save(string $destination): bool;

    public function savePlacePicture(Place $place): string;
}
