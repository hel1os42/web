<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\SearchOfferRequest;

class SearchOfferController extends Controller
{
    /**
     * Get default data for search request
     * @return Response
     */
    public function index(): Response
    {
        response()->render('offer.search', [
            'latitude'  => null,
            'longitude' => null,
            'radius'    => 1
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

    }

}