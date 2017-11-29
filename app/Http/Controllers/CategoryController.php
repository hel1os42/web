<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $this->authorize('categories.list');

        $categories = $categoryRepository
            ->getWithNoParent();

        return \response()->render('category.list', $categories->paginate());
    }

    /**
     * Category show
     *
     * @param string             $uuid
     * @param CategoryRepository $categoryRepository
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $uuid, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository
            ->with(['parent'])->find($uuid);

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $this->authorize('categories.show', $category);

        return response()->render('category.show', $category->toArray());
    }
}
