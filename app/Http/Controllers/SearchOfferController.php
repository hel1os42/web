<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\SearchOfferRequest;

class SearchOfferController extends Controller
{
    /**
     * Get default data for search request
     * @return Response
     */
    public function index() : Response
    {
        response()->render('', [
            'latitude' => null,
            'longitude' => null,
            'radius' => null,
            'category' => null,
            'city' => null,
            'country' => null,
            'name' => null,
            'description' => null,
            'data' => [
                'categories' => [] //  get all categories from model
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
        //$offers =

        /*
         * filter by distance
            6371000 * 2 * ASIN(SQRT(
                POWER(SIN((lat1 - ABS(lat2)) * PI()/180 / 2), 2) +
                COS(lat1 * PI()/180) *
                COS(ABS(lat2) * PI()/180) *
                POWER(SIN((lon1 - lon2) * PI()/180 / 2), 2)
            )) < r1 + r2
        */
    }

}