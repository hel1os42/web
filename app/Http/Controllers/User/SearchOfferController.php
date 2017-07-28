<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\User\SearchOfferRequest;

class SearchOfferController extends Controller
{
    /**
     * Create search request
     * @return Response
     */
    public function index(): Response
    {
        return response()->render('user.offer.search', [
            'data' => [
                'latitude'  => null,
                'longitude' => null,
                'radius'    => 1,
                'results' => null
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
        $offers = $offers->filterByPosition($request->latitude, $request->longitude, $request->radius)->get();
        return response()->render('user.offer.search', [
            'data' => [
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'radius'    => $request->radius,
                'results' => $offers
            ]
        ]);
    }
}
