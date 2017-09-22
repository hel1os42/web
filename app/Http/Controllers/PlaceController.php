<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceCreateRequest;
use App\Http\Requests\PlaceUpdateRequest;
use App\Models\Currency;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PlaceController extends Controller
{

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

    public function showOwnerPlace(Request $request, string $uuid = null): Response
    {
        $place = Place::findByUserId(auth()->id());
        if (!$place instanceof Place) {
            throw new BadRequestHttpException('You have not created a place yet.');
        }
        return \response()->render('place.show', $place->toArray());
    }

    public function create(): Response
    {
        return \response()->render('place.create', (new Place)->toArray());
    }

    public function store(PlaceCreateRequest $request): Response
    {
        if (Place::findByUserId(auth()->id()) instanceof Place) {
            throw new BadRequestHttpException('You already create place.');
        }

        $place = new Place();
        $place = $place->create($request->all());
        return \response()->render('place.show.my',
            $place->toArray(),
            Response::HTTP_CREATED,
            route('place.show.my'));
    }

    public function update(PlaceUpdateRequest $request): Response
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
