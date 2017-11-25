<?php

namespace App\Http\Controllers;

use App\Helpers\Attributes;
use App\Helpers\FormRequest;
use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Models\NauModels\Offer;
use App\Repositories\OfferRepository;
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
     * @param OfferRepository    $offerRepository
     *
     * @return Response
     */
    public function index(PlaceFilterRequest $request, OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository
            ->skipCriteria()
            ->getActiveByCategoriesAndPosition($request->category_ids,
                $request->latitude, $request->longitude, $request->radius)
            ->select('acc_id')
            ->groupBy('acc_id', 'lat', 'lng');

        if (isset($request->latitude, $request->longitude)) {
            $offers->orderByRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((lat - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(lat * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((lng - %2$s) * 
        PI()/180 / 2), 2))))',
                \DB::connection()->getPdo()->quote($request->latitude),
                \DB::connection()->getPdo()->quote($request->longitude)))
                   ->groupBy('lat', 'lng');
        }

        $paginator = $offers->paginate();

        $array         = $paginator->toArray();
        $array['data'] = $offers->get()->map(function (Offer $offer) {
            return $offer->getOwner()->place;
        });

        return response()->render('place.index', $array);
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
    public function create(): Response
    {
        if ($this->placesRepository->existsByUser($this->auth->user())) {
            throw new AuthorizationException('This action is unauthorized.');
        }

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
