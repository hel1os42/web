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
 *
 * @package App\Http\Controllers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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

        $placeRepository->setPresenter(new \App\Presenters\PlacePresenter($this->auth));

        $places         = $places->paginate();
        $places['data'] = $placeRepository->parsePaginatedResult($places)['data'];

        return response()->render('place.index', $places);
    }

    /**
     * @param Request         $request
     * @param PlaceRepository $placesRepository
     * @param string|null     $uuid
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
        $place->setPresenter(new \App\Presenters\PlacePresenter($this->auth));
        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        $this->authorize('places.show', $place);

        return \response()->render('place.show', $place->presenter()['data']);
    }

    /**
     * @param Request         $request
     * @param PlaceRepository $placesRepository
     * @param string|null     $uuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function edit(Request $request, PlaceRepository $placesRepository, string $uuid = null): Response
    {
        $place = is_null($uuid)
            ? $placesRepository->findByUser($this->user())
            : $placesRepository->find($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        $this->authorize('places.update', $place);

        return \response()->render('place.edit', $place->toArray());
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
     * @param CreateUpdateRequest $request
     * @param UserRepository      $userRepository
     * @param PlaceRepository     $placesRepository
     * @param PlaceService        $placeService
     * @param string|null         $userUuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function store(
        CreateUpdateRequest $request,
        UserRepository $userRepository,
        PlaceRepository $placesRepository,
        PlaceService $placeService,
        string $userUuid = null)
    : Response {
        $forUser = is_null($userUuid)
            ? $this->user()
            : $userRepository->find($userUuid);

        $this->authorize('places.create', $forUser);

        $placeData = $request->except('specialities', 'tags');

        $specsIds = $placeService->parseSpecialities($request->get('specialities', []));
        $tagsIds  = $placeService->parseTags($placeData['category'], $request->get('tags', []));

        $place = $placesRepository->createForUserOrFail($placeData, $this->user(), $specsIds, $tagsIds);

        return \response()->render('place.show',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('places.show', [$place->getId()]));
    }

    /**
     * @param CreateUpdateRequest $request
     * @param PlaceRepository     $placesRepository
     * @param PlaceService        $placeService
     * @param string|null         $uuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(
        CreateUpdateRequest $request,
        PlaceRepository $placesRepository,
        PlaceService $placeService,
        string $uuid = null
    ): Response {
        $place = is_null($uuid)
            ? $placesRepository->findByUser($this->user())
            : $placesRepository->find($uuid);

        $this->authorize('places.update', $place);

        $placeData = $request->except('specialities', 'tags');
        $specsIds  = $placeService->parseSpecialities($request->get('specialities', []));
        $tagsIds   = $placeService->parseTags($placeData['category'], $request->get('tags', []));

        if ($request->isMethod('put')) {
            $placeData = array_merge($place->getFillableWithDefaults(), $placeData);
        }

        if (!$this->user()->isAgent() && !$this->user()->isAdmin()) {
            $placeService->disapprove($place, true);
        }

        $place = $placesRepository->updateWithRelations($placeData, $place->id, $specsIds, $tagsIds);

        return \response()->render('place.show', $place->toArray(), Response::HTTP_CREATED,
            route('places.show', [$place->getId()]));
    }
}
