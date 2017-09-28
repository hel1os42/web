<?php

namespace App\Http\Controllers;

use App\Helpers\FormRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Http\Requests\PlaceRequest;
use App\Models\Place;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @throws \InvalidArgumentException
     * @throws \LogicException
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

        if (null === $place) {
            throw new NotFoundHttpException('You have not created a place yet.');
        }

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
     * @return Response
     */
    public function create(): Response
    {
        $place = $this->placesRepository->findByUser($this->auth->user());

        if (null !== $place) {
            throw new BadRequestHttpException('You\'ve already created a place.');
        }

        return \response()->render('place.create', FormRequest::preFilledFormRequest(PlaceRequest::class));
    }

    /**
     * @param PlaceRequest $request
     *
     * @return Response
     */
    public function store(PlaceRequest $request): Response
    {
        if ($this->placesRepository->existsByUser($this->auth->user())) {
            throw new BadRequestHttpException('You\'ve already created a place.');
        }

        $placeData = $request->all();

        $place = $this->placesRepository->createForUser($placeData, $this->auth->user());

        if (null === $place->id) {
            logger()->error('cannot save place', $place->toArray());
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Cannot save place');
        }

        if ($request->has('category_ids') === true) {
            $place->categories()->attach($request->category_ids);
        }

        return \response()->render('profile.place.show',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('profile.place.show'));
    }

    /**
     * @param PlaceRequest $request
     *
     * @return Response
     */
    public function update(PlaceRequest $request): Response
    {
        $place = $this->placesRepository->findByUser($this->auth->user());

        if (null === $place) {
            throw new BadRequestHttpException('You\'ve not created a place yet.');
        }

        $placeData = $request->all();

        if ($request->isMethod('put')) {
            $placeData = array_merge(Place::getFillableWithDefaults(), $placeData);
        }

        $place = $this->placesRepository->update($placeData, $place->id);
        $place->categories()->sync($request->category_ids);

        return \response()->render('profile.place.show', $place->toArray(), Response::HTTP_CREATED,
            route('profile.place.show'));
    }
}
