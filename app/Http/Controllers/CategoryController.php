<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use HandlesRequestData;

    /**
     * @return Response
     */
    public function index(): Response
    {
        return \response()->render('category.list', Category::with(['parent'])->paginate());
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
    public function show(Request $request, string $uuid)
    {
        $with     = $this->handleWith(
            ['children'],
            $request,
            ['parent']
        );
        $category = Category::with($with)->findOrFail($uuid);

        return response()->render('category.show', $category->toArray());
    }
}
