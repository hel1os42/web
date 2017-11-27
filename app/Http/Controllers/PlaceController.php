<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Helpers\FormRequest;
use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Repositories\PlaceRepository;
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
     * @param PlaceRepository    $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(PlaceFilterRequest $request, PlaceRepository $placesRepository): Response
    {
        $this->authorize('places.list');

        $places = $placesRepository
            ->getByCategoriesAndPosition($request->category_ids,
                $request->latitude, $request->longitude, $request->radius);

        return response()->render('place.index', $places->paginate());
    }

    /**
     * @param Request         $request
     * @param string          $uuid
     * @param PlaceRepository $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function show(Request $request, string $uuid, PlaceRepository $placesRepository): Response
    {
        $place = $placesRepository->find($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        $this->authorize('places.show', $place);

        return \response()->render('place.show', $place->toArray());
    }

    /**
     * @param Request         $request
     * @param PlaceRepository $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function showOwnerPlace(Request $request, PlaceRepository $placesRepository): Response
    {

        $this->authorize('my.place.show');

        $place = $placesRepository->findByUser($this->auth->user());

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('profile.place.show', $place->toArray());
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
     * @param PlaceRepository $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(PlaceRepository $placesRepository): Response
    {
        $this->authorize('my.place.create');

        if ($placesRepository->existsByUser($this->auth->user())) {
            return \response()->error(Response::HTTP_NOT_ACCEPTABLE, 'You\'ve already created a place.');
        }

        return \response()->render('place.create', FormRequest::preFilledFormRequest(CreateUpdateRequest::class));
    }

    /**
     * @param CreateUpdateRequest $request
     * @param PlaceRepository     $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \LogicException
     */
    public function store(CreateUpdateRequest $request, PlaceRepository $placesRepository): Response
    {
        $this->authorize('my.place.create');

        $placeData = $request->all();

        $place = $placesRepository->createForUserOrFail($placeData, $this->auth->user());

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
     * @param PlaceRepository     $placesRepository
     *
     * @return Response
     * @throws AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function update(CreateUpdateRequest $request, PlaceRepository $placesRepository): Response
    {
        $this->authorize('my.place.update');

        $place = $placesRepository->findByUser($this->auth->user());

        $placeData = $request->all();

        if ($request->isMethod('put')) {
            $placeData = array_merge(Attributes::getFillableWithDefaults($place), $placeData);
        }

        $place = $placesRepository->update($placeData, $place->id);
        $place->categories()->sync($request->category_ids);

        return \response()->render('profile.place.show', $place->toArray(), Response::HTTP_CREATED,
            route('profile.place.show'));
    }
}
