<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class OfferController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function create()
    {
        response()->render('offer.create', [
            'name' => null,
            'description' => null,
            'reward' => 1,
            'dateStart' => Carbon::createFromDate(),
            'timeStart' => Carbon::createFromTime(),
            'datefinish' => null,
            'timefinish' => null,
            'category' => 'Test', //todo @mobixon create category table+model
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        response()->render('offer.show', [
            'name' => $request->name
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
}
