<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Place;
use Illuminate\Http\Request;
use Pheanstalk\Exception\ServerInternalErrorException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlaceController extends Controller
{
    use HandlesRequestData;

    public function index(Request $request): Response
    {
        return response()->render('place.list',
            Place::with('categories')
                ->filterByCategories($request->get('category_ids', []))
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
        return \response()->render('place.show', Place::with($with)->findOrFail($uuid)->toArray());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showOwnerPlace(Request $request): Response
    {
        $with  = $this->handleWith(
            ['testimonials', 'categories'],
            $request
        );
        $place = Place::with($with)->findByUserId(auth()->id());
        if (!$place instanceof Place) {
            return \response()->error(Response::HTTP_NOT_FOUND, 'You have not created a place yet.');
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
        return \response()->render('place.show', Place::findByUserId(auth()->id())->getOffers()->toArray());
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('place.create', array_merge((new Place)->getFillable(), ['categories']));
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     * @throws ServerInternalErrorException
     */
    public function store(PlaceRequest $request): Response
    {
        if (Place::findByUserId(auth()->id()) instanceof Place) {
            throw new BadRequestHttpException('You already create place.');
        }

        $place = $request->fillPlace(new Place());
        $place->user()->associate(auth()->user());
        if (!$place->save()) {
            logger()->error('cannot save place', $place->toArray());
            throw new ServerInternalErrorException();
        }
        if ($request->has('categories') === true) {
            $place->categories()->attach($request->categories);
        }

        return \response()->render('place.show.my',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('places.show.my'));
    }

    /**
     * @param PlaceRequest $request
     * @return Response
     * @throws ServerInternalErrorException
     */
    public function update(PlaceRequest $request): Response
    {
        $place = Place::findByUserId(auth()->id());
        if (!$place instanceof Place) {
            throw new BadRequestHttpException('You have not created a place yet.');
        }

        $success = $request->isMethod('put') ?
            $place->update(array_merge((new Place)->getFillable(), $request->all())) :
            $place->update($request->all());

        if ($request->has('categories') === true) {
            $place->categories()->sync($request->categories);
        }

        if (!$success) {
            logger()->error('cannot update place', $place->toArray());
            throw new ServerInternalErrorException();
        }
        return \response()->render('place.show.my', $place->toArray(), Response::HTTP_CREATED, route('place.show.my'));
    }
}
