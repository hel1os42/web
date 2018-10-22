<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateUpdateRequest;
use App\Repositories\TagRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TagController constructor.
     *
     * @param AuthManager   $authManager
     * @param TagRepository $tagRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthManager $authManager, TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
        parent::__construct($authManager);
    }

    /**
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('tags.list');

        return \response()->render('tag.index', ['data' => $this->tagRepository->orderBy('name')->all()->toArray()]);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function create()
    {
        $this->authorize('tags.create');

        return \response()->render('tag.create', \App\Helpers\Attributes::getFillableWithDefaults(new \App\Models\Tag));
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
        $this->authorize('tags.create');

        $tag = $this->tagRepository->create($request->all());

        return \response()->render('tag.show', $tag->fresh('category')->toArray(), Response::HTTP_CREATED, route('tags.show', $tag->getId()));
    }

    /**
     * @param string $tagId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function edit(string $tagId): Response
    {
        $tag = $this->tagRepository->with(['category'])->find($tagId);
        $this->authorize('tags.update', $tag);

        return \response()->render('tag.edit', $tag->toArray());
    }

    /**
     * @param CreateUpdateRequest $request
     * @param string              $tagId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function update(CreateUpdateRequest $request, string $tagId)
    {
        $tag = $this->tagRepository->find($tagId);

        $this->authorize('tags.update', $tag);

        $tag = $this->tagRepository->with('category')->update($request->all(), $tag->getId());

        return \response()->render('tag.show', $tag->toArray(), Response::HTTP_ACCEPTED, route('tags.show', $tag->getId()));
    }

    /**
     * @param int $tagId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(int $tagId)
    {
        $tag = $this->tagRepository->with(['category'])->find($tagId);

        $this->authorize('tags.show', $tag);

        return response()->render('tag.show', $tag->toArray());
    }
}
