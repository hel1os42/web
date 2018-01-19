<?php

namespace App\Http\Controllers;

use App\Helpers\FormRequest;
use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Repositories\PlaceRepository;
use App\Repositories\UserRepository;
use App\Services\PlaceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlaceController
 * @package App\Http\Controllers
 */
class PlaceController extends Controller
{
    /**
     * @param PlaceFilterRequest $request
     * @param PlaceRepository    $placeRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(PlaceFilterRequest $request, PlaceRepository $placeRepository): Response
    {
        $this->authorize('places.list');

        $places = $placeRepository->getActiveByCategoriesAndPosition($request->category_ids, $request->latitude,
            $request->longitude, $request->radius);

        return response()->render('places.index', $places->paginate());
    }

    /**
     * @param Request         $request
     * @param string          $uuid
     * @param PlaceRepository $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function show(Request $request, PlaceRepository $placesRepository, string $uuid = null): Response
    {
        $place = is_null($uuid)
            ? $placesRepository->findByUser($this->user())
            : $placesRepository->find($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        $this->authorize('places.show', $place);

        return \response()->render('places.show', $place->toArray());
    }

    /**
     * @param Request         $request
     * @param PlaceRepository $placesRepository
     * @param string|null     $uuid
     *
     * @return mixed
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function edit(Request $request, PlaceRepository $placesRepository, string $uuid = null)
    {
        $place = is_null($uuid)
            ? $placesRepository->findByUser($this->user())
            : $placesRepository->find($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        $this->authorize('places.update', $place);

        return \response()->render('places.edit', $place->toArray());
    }

    /**
     * @param string          $uuid
     * @param PlaceRepository $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \App\Exceptions\TokenException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function showPlaceOffers(string $uuid, PlaceRepository $placesRepository): Response
    {
        $place = $placesRepository->find($uuid);

        $this->authorize('places.offers.list', $place);

        $offers = $place->offers();

        return \response()->render('user.offer.index', $offers->paginate());
    }

    /**
     * @param UserRepository  $userRepository
     * @param PlaceRepository $placesRepository
     * @param string|null     $userUuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(UserRepository $userRepository, PlaceRepository $placesRepository, string $userUuid = null): Response
    {
        $forUser = is_null($userUuid)
            ? $this->user()
            : $userRepository->find($userUuid);

        $this->authorize('places.create', $forUser);

        if ($placesRepository->existsByUser($forUser)) {
            return \response()->error(Response::HTTP_NOT_ACCEPTABLE, 'You\'ve already created a place.');
        }

        return \response()->render('place.create', FormRequest::preFilledFormRequest(CreateUpdateRequest::class));
    }

    /**
     * @param UserRepository      $userRepository
     * @param CreateUpdateRequest $request
     * @param PlaceRepository     $placesRepository
     * @param string|null         $userUuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function store(UserRepository $userRepository, CreateUpdateRequest $request, PlaceRepository $placesRepository, string $userUuid = null): Response
    {
        $forUser = is_null($userUuid)
            ? $this->user()
            : $userRepository->find($userUuid);

        $this->authorize('places.create', $forUser);

        $placeData = $request->all();

        $place = $placesRepository->createForUserOrFail($placeData, $forUser);

        if ($request->has('category_ids') === true) {
            $place->categories()->attach($request->category_ids);
        }

        return \response()->render('place.show',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('place.show', [$place->getId()]));
    }

    /**
     * @param CreateUpdateRequest $request
     * @param PlaceRepository     $placesRepository
     * @param PlaceService        $placeService
     * @param string|null         $uuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function update(
        CreateUpdateRequest $request,
        PlaceRepository $placesRepository,
        PlaceService $placeService,
        string $uuid = null
    ): Response
    {
        $place = is_null($uuid)
            ? $placesRepository->findByUser($this->user())
            : $placesRepository->find($uuid);

        $this->authorize('places.update', $place);

        $placeData = $request->all();

        if ($request->isMethod('put')) {
            $placeData = array_merge($place->getFillableWithDefaults(), $placeData);
        }

        if (!$this->user()->isAgent() && !$this->user()->isAdmin()) {
            $placeService->disapprove($place, true);
        }

        $place = $placesRepository->update($placeData, $place->id);
        $place->categories()->sync($request->category_ids);

        return \response()->render('place.show', $place->toArray(), Response::HTTP_CREATED,
            route('place.show', [$place->getId()]));
    }
}
