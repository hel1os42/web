<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('index', $this->categoryRepository->model());

        $categories = $this->categoryRepository
            ->getWithNoParent();

        if ($categories === null) {
            throw new NotFoundHttpException();
        }

        return \response()->render('category.list', $categories->paginate());
    }

    /**
     * Category show
     *
     * @param string $uuid
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $uuid)
    {
        $this->authorize('show', $this->categoryRepository->model());

        $category = $this->categoryRepository
            ->with(['parent'])->find($uuid);

        if ($category === null) {
            throw new NotFoundHttpException();
        }

        return response()->render('category.show', $category->toArray());
    }
}
