<?php

namespace App\Console\Commands;

use App\Repositories\PlaceRepository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;

class ConvertPlacePicturesToPng extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:convert-place-pictures-to-png';

    private $filesystem;

    private $imageManager;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert place pictures from jpg to png';

    public function __construct(Filesystem $filesystem, ImageManager $imageManager)
    {

        $this->filesystem   = $filesystem;
        $this->imageManager = $imageManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param PlaceRepository $placeRepository
     *
     * @throws \Intervention\Image\Exception\NotReadableException
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function handle(PlaceRepository $placeRepository)
    {
        $places = $placeRepository->all();

        foreach ($places as $place) {
            $path    = $this->picturePathFor($place->getId(), 'jpg');
            $newPath = $this->picturePathFor($place->getId(), 'png');

            if ($this->filesystem->exists($path)) {
                $picture = $this->imageManager
                    ->make(storage_path('app/' . $path))
                    ->encode('png');
                if (!$this->filesystem->exists($newPath)) {
                    $this->filesystem->put($newPath, $picture);
                }
                $this->filesystem->delete($path);
            }
        }
    }

    private function picturePathFor(string $uuid, string $formzt): string
    {
        return sprintf('images/place/pictures/%s.%s', $uuid, $formzt);
    }
}
