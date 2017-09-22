<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Place;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlaceController extends Controller
{
    use HandlesRequestData;

    public function index(): Response
    {
        return \response()->render('place.list', Place::all()->paginate());
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return Response
     */
    public function show(Request $request, string $uuid): Response
    {
        return \response()->render('place.show', Place::findOrFail($uuid)->toArray());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showOwnerPlace(Request $request): Response
    {
        $with = $this->handleWith(
            ['testimonials'],
            $request
        );
        $place = Place::query()->with($with)->findByUserId(auth()->id());
        if (!$place instanceof Place) {
            throw new BadRequestHttpException('You have not created a place yet.');
        }
        return \response()->render('place.show', $place->toArray());
    }

    /**
     * @param Request $request
     * @param string|null $uuid
     * @return Response
     */
    public function showPlaceOffers(Request $request, string $uuid): Response
    {
        return \response()->render('place.show', Place::findOrFail($uuid)->getOffers()->toArray());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showOwnerPlaceOffers(Request $request): Response
    {
        return \response()->render('place.show', Place::findByUserId(auth()->id())->getOffers()->toArray());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('place.create', (new Place)->toArray());
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     */
    public function store(PlaceRequest $request): Response
    {
        if (Place::findByUserId(auth()->id()) instanceof Place) {
            throw new BadRequestHttpException('You already create place.');
        }

        $place = new Place();
        $place->fill($request->all());
        $place->user()->associate(auth()->user());
        $place->save();
        return \response()->render('place.show.my',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('places.show.my'));
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     */
    public function update(PlaceRequest $request): Response
    {
        $place = Place::findByUserId(auth()->id());
        if (!$place instanceof Place) {
            throw new BadRequestHttpException('You have not created a place yet.');
        }

        $success = $request->isMethod('put') ?
            $place->update(array_merge((new Place)->toArray(), $request->all())) :
            $place->update($request->all());

        return $success ?
            \response()->render('place.show.my', $place->toArray(), Response::HTTP_CREATED, route('place.show.my')) :
            \response()->error(Response::HTTP_NO_CONTENT);

    }
}
