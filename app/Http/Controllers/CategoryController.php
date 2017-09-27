<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @param Request $request
     *
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $categories = $this->categoryRepository
            ->getWithNoParent();

        return \response()->render('category.list', $categories->paginate());
    }

    /**
     * Category show
     *
     * @param Request $request
     * @param string  $uuid
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function show(string $uuid)
    {
        $category = $this->categoryRepository
            ->with(['parent'])->find($uuid);

        return response()->render('category.show', $category->toArray());
    }
}
