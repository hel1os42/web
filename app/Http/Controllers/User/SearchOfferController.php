<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\SearchOfferRequest;

class SearchOfferController extends Controller
{
    /**
     * Create search request
     * @return Response
     */
    public function index(): Response
    {
        return response()->render('user.offer.search', [
            'data' => (object)[
                'latitude'  => null,
                'longitude' => null,
                'radius'    => 1
            ]
        ]);
    }

    /**
     * Search offers
     * @param SearchOfferRequest $request
     * @return Response
     */
    public function search(SearchOfferRequest $request)
    {
        $offers = new Offer();
        $offers->filterByPosition($request->latitude, $request->longitude, $request->radius)->get();
        dd($offers);
        return true;
    }

}