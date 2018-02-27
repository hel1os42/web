<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateUpdateRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryController constructor.
     *
     * @param AuthManager        $authManager
     * @param CategoryRepository $categoryRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthManager $authManager, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($authManager);
    }

    /**
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function mainCategories(): Response
    {
        $this->authorize('categories.list');

        $categories = $this->categoryRepository
            ->getWithNoParent()
            ->get()
            ->toArray();

        return \response()->render('category.list', ['data' => $categories]);
    }

    /**
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('categories.list');

        return \response()->render('category.index', ['data' => $this->categoryRepository->ordered()->get()->toArray()]);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function create()
    {
        $this->authorize('categories.create');

        return \response()->render('category.create', \App\Helpers\Attributes::getFillableWithDefaults(new \App\Models\Category));
    }

    /**
     * @param CreateUpdateRequest $request
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(CreateUpdateRequest $request)
    {
        $this->authorize('categories.create');

        $category = $this->categoryRepository->create($request->all());

        return \response()->render('category.show', $category->fresh('parent')->toArray(), Response::HTTP_CREATED, route('categories.show', $category->getId()));
    }

    /**
     * @param string $categoryId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function edit(string $categoryId): Response
    {
        $category = $this->categoryRepository->with(['parent'])->find($categoryId);
        $this->authorize('categories.update', $category);

        return \response()->render('category.edit', $category->toArray());
    }

    /**
     * @param CreateUpdateRequest $request
     * @param string              $categoryId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function update(CreateUpdateRequest $request, string $categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        $this->authorize('categories.update', $category);

        $category = $this->categoryRepository->with('parent')->update($request->all(), $category->getId());

        return \response()->render('category.show', $category->toArray(), Response::HTTP_ACCEPTED, route('categories.show', $category->getId()));
    }

    /**
     * @param string $uuid
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $uuid)
    {
        $category = $this->categoryRepository
            ->with(['parent'])
            ->find($uuid);

        $this->authorize('categories.show', $category);

        return response()->render('category.show', $category->toArray());
    }
}
