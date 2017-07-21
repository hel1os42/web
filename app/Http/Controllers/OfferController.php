<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Offer;

class OfferController extends Controller
{

    /**
     * Get offer list of this user
     * @return Response
     */
    public function index() : Response
    {
        //get offer list
    }

    /**
     * Get the form/json data for creating a new offer.
     * @return Response
     */
    public function create() : Response
    {
        response()->render('offer.create', [
            'name' => null,
            'description' => null,
            'reward' => 1,
            'dateStart' => Carbon::createFromDate(),
            'timeStart' => Carbon::createFromTime(),
            'datefinish' => null,
            'timefinish' => null,
            'category' => 'Test', // todo @mobixon create category table+model
            'max_count' => 10,
            'max_for_user' => 1,
            'max_per_day' => 10,
            'max_for_user_per_day' => 1,
            'min_level' => 1,
            'latitude' => null,
            'longitude' => null,
            'radius' => null,
            'country' => null,
            'city' => null
        ]);
    }

    /**
     * Send new offer data to core to store
     * @param  \App\Http\Requests\OfferRequest $request
     * @return Response
     */
    public function store(\App\Http\Requests\OfferRequest $request) : Response
    {
        //todo @mobixon core servise method
        response()->render('offer.show', [
            'name' => $request->name
            //
            //            'description' => null,
            //            'reward' => 1,
            //            'date_start' => Carbon::createFromDate(),
            //            'time_start' => Carbon::createFromTime(),
            //            'date_finish' => null,
            //            'time_finish' => null,
            //            'category' => 'Test',
            //            'max_count' => 10,
            //            'max_for_user' => 1,
            //            'max_per_day' => 10,
            //            'max_for_user_per_day' => 1,
            //            'min_level' => 1,
            //            'latitude' => null,
            //            'longitude' => null,
            //            'radius' => null,
            //            'country' => null,
            //            'city' => null
        ]);
    }

    /**
     * Get offer by it uuid
     * @param string $offerUuid
     * @return Response
     */
    public function show(string $offerUuid) : Response
    {
        // todo @mobixon check: is current user author of this offer
        response()->render('offer.show', [
            'offer' => Offer::find($offerUuid)->fresh()
            ]);
    }
}
