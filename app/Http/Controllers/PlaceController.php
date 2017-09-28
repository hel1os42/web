<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Helpers\FormRequest;
use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Repositories\PlaceRepository;
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
     */
    public function index(PlaceFilterRequest $request): Response
    {
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
     */
    public function show(Request $request, string $uuid): Response
    {
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
     */
    public function showOwnerPlace(Request $request): Response
    {
        $place = $this->placesRepository->findByUser($this->auth->user());

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('profile.place.show', $place->toArray());
    }

    /**
     * @param string|null $uuid
     *
     * @return Response
     */
    public function showPlaceOffers(string $uuid): Response
    {
        $offers = $this->placesRepository->find($uuid)->offers();

        return \response()->render('user.offer.index', $offers->paginate());
    }

    /**
     * @return Response
     */
    public function showOwnerPlaceOffers(): Response
    {
        $place = $this->placesRepository->findByUser($this->auth->user());

        return \response()->render('advert.offer.index', $place->offers()->paginate());
    }

    /**
     * @param CreateUpdateRequest $request
     *
     * @return Response
     */
    public function create(CreateUpdateRequest $request): Response
    {
        $request->validate();
        return \response()->render('place.create', FormRequest::preFilledFormRequest(CreateUpdateRequest::class));
    }

    /**
     * @param CreateUpdateRequest $request
     *
     * @return Response
     */
    public function store(CreateUpdateRequest $request): Response
    {
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
     */
    public function update(CreateUpdateRequest $request): Response
    {
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
