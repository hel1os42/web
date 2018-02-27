<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\AbstractPictureController;
use App\Http\Requests\Profile\PictureRequest;
use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Categories
 */
class PictureController extends AbstractPictureController
{
    const CATEGORY_PICTURES_PATH = 'images/category/pictures';

    protected $pictureFormat = 'svg';

    protected $pictureMimeTypes = [
        'svg' => 'image/svg+xml',
    ];

    /**
     * @param PictureRequest     $request
     * @param string             $categoryId
     * @param CategoryRepository $categoryRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function store(PictureRequest $request, string $categoryId, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($categoryId);
        $this->authorize('categories.picture.store', $category);

        $request->file('picture')->storePubliclyAs(self::CATEGORY_PICTURES_PATH . '/', $categoryId . '.svg');

        return $request->wantsJson()
            ? \response()->render('', [], Response::HTTP_CREATED, route('categories.show', $categoryId))
            : \redirect()->route('categories.show', $categoryId);

    }

    /**
     * @param string             $categoryId
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function show(string $categoryId, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($categoryId);

        return $this->respondWithImageFor($category->getId());
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return self::CATEGORY_PICTURES_PATH;
    }
}
