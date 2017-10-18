<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Helpers\FormRequest;
use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlaceController
 * @package App\Http\Controllers
 */
class PlaceController extends Controller
{
    private $placesRepository;
    private $auth;

    public function __construct(PlaceRepository $placesRepository, AuthManager $authManager)
    {
        $this->placesRepository = $placesRepository;
        $this->auth             = $authManager->guard();
    }

    /**
     * @param PlaceFilterRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(PlaceFilterRequest $request): Response
    {
        $this->authorize('index', $this->placesRepository->model());

        $places = $this->placesRepository
            ->getByCategoriesAndPosition($request->category_ids,
                $request->latitude, $request->longitude, $request->radius);

        return response()->render('place.index', $places->paginate());
    }

    /**
     * @param Request $request
     * @param string  $uuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function show(Request $request, string $uuid): Response
    {
        $this->authorize('show', $this->placesRepository->model());

        $place = $this->placesRepository
            ->find($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('place.show', $place->toArray());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function showOwnerPlace(Request $request): Response
    {
        $this->authorize('showOwnerPlace', $this->placesRepository->model());

        $place = $this->placesRepository->findByUser($this->auth->user());

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('profile.place.show', $place->toArray());
    }

    /**
     * @param string $uuid
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \App\Exceptions\TokenException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function showPlaceOffers(string $uuid): Response
    {
        $this->authorize('showPlaceOffers', $this->placesRepository->model());

        $offers = $this->placesRepository->find($uuid)->offers();

        return \response()->render('user.offer.index', $offers->paginate());
    }

    /**
     * @return Response
     * @throws AuthorizationException
     * @throws \App\Exceptions\TokenException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function showOwnerPlaceOffers(): Response
    {
        $this->authorize('showOwnerPlaceOffers', $this->placesRepository->model());

        $place = $this->placesRepository->findByUser($this->auth->user());

        return \response()->render('advert.offer.index', $place->offers()->paginate());
    }

    /**
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(): Response
    {
        $this->authorize('create', $this->placesRepository->model());

        if ($this->placesRepository->existsByUser($this->auth->user())) {
            return \response()->error(Response::HTTP_NOT_ACCEPTABLE, 'You\'ve already created a place.',
                route('profile.place.show'));
        }

        return \response()->render('place.create', FormRequest::preFilledFormRequest(CreateUpdateRequest::class));
    }

    /**
     * @param CreateUpdateRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function store(CreateUpdateRequest $request): Response
    {
        $this->authorize('store', $this->placesRepository->model());

        $placeData = $request->all();

        $place = $this->placesRepository->createForUserOrFail($placeData, $this->auth->user());

        if ($request->has('category_ids') === true) {
            $place->categories()->attach($request->category_ids);
        }

        return \response()->render('profile.place.show',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('profile.place.show'));
    }

    /**
     * @param CreateUpdateRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function update(CreateUpdateRequest $request): Response
    {
        $this->authorize('store', $this->placesRepository->model());

        $place = $this->placesRepository->findByUser($this->auth->user());

        $placeData = $request->all();

        if ($request->isMethod('put')) {
            $placeData = array_merge(Attributes::getFillableWithDefaults($place), $placeData);
        }

        $place = $this->placesRepository->update($placeData, $place->id);
        $place->categories()->sync($request->category_ids);

        return \response()->render('profile.place.show', $place->toArray(), Response::HTTP_CREATED,
            route('profile.place.show'));
    }
}
