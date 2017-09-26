<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceFilterRequest;
use App\Http\Requests\PlaceRequest;
use App\Models\Place;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlaceController extends Controller
{
    use HandlesRequestData;

    public function index(PlaceFilterRequest $request): Response
    {
        return response()->render('place.list',
            Place::filterByCategories($request->category_ids)
                ->filterByPosition($request->latitude, $request->longitude, $request->radius)
                ->paginate()
        );
    }

    /**
     * @param Request $request
     * @param string $uuid
     * @return Response
     */
    public function show(Request $request, string $uuid): Response
    {
        $with = $this->handleWith(
            ['testimonials', 'categories'],
            $request
        );

        $place = Place::with($with)->findOrFail($uuid);

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('place.show', $place->toArray());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showOwnerPlace(Request $request): Response
    {
        $with = $this->handleWith(
            ['testimonials', 'categories'],
            $request
        );

        $place = Place::with($with)->byUser(auth()->user())->first();
        if (!$place instanceof Place) {
            return \response()->error(Response::HTTP_NOT_FOUND, 'You have not created a place yet.');
        }

        if (in_array('offers', explode(',', $request->get('with', '')))) {
            $place->append('offers');
        }

        return \response()->render('place.show', $place->toArray());
    }

    /**
     * @param string|null $uuid
     * @return Response
     */
    public function showPlaceOffers(string $uuid): Response
    {
        return \response()->render('place.show', Place::findOrFail($uuid)->getOffers()->toArray());
    }

    /**
     * @return Response
     */
    public function showOwnerPlaceOffers(): Response
    {
        return \response()->render('place.show', Place::byUser(auth()->user())->firstOrFail()->getOffers()->toArray());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('place.create', array_merge(Place::getFillableWithDefaults(), ['category_ids' => []]));
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     */
    public function store(PlaceRequest $request): Response
    {
        if (Place::byUser(auth()->user())->first() instanceof Place) {
            throw new BadRequestHttpException('You already create place.');
        }

        $place = $request->fillPlace(new Place());
        $place->user()->associate(auth()->user());
        if (!$place->save()) {
            logger()->error('cannot save place', $place->toArray());
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Cannot save place');
        }
        if ($request->has('category_ids') === true) {
            $place->categories()->attach(explode(',', $request->category_ids));
        }

        return \response()->render('place.show',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('places.show', ['uuid' => $place->getId()]));
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     */
    public function update(PlaceRequest $request): Response
    {
        $place = Place::byUser(auth()->user())->first();
        if (!$place instanceof Place) {
            throw new BadRequestHttpException('You have not created a place yet.');
        }

        $success = $request->isMethod('put') ?
            $place->update(array_merge(Place::getFillableWithDefaults(), $request->all())) :
            $place->update($request->all());

        if ($request->has('category_ids') === true) {
            $place->categories()->sync($request->category_ids);
        }

        if (!$success) {
            logger()->error('cannot update place', $place->toArray());
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Cannot update place');
        }
        return \response()->render('place.list', $place->toArray(), Response::HTTP_CREATED, route('places.show', ['uuid' => $place->getId()]));
    }
}
