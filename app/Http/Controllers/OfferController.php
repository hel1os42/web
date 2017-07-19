<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class OfferController extends Controller
{
    /**
     * Display offer listing.
     *
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->error(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function create()
    {
        response()->render('');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        return response()->error(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function edit(string $id)
    {
        return response()->error(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request,string $id)
    {
        return response()->error(404);
    }

}
