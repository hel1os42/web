<?php

namespace App\Http\Controllers;

use App\Models\Redemption;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Offer;

class OfferController extends Controller
{

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     */
    public function index(): Response
    {
        //get offer list
        return \response()->error('404');
    }


    /**
     * Get the form/json data for creating a new offer.
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('offer.create', [
            'data' => new Offer()
        ]);
    }

    /**
     * Send new offer data to core to store
     * @param  \App\Http\Requests\OfferRequest $request
     * @return Response
     */
    public function store(\App\Http\Requests\OfferRequest $request): Response
    {
        $newOffer = new Offer();
        $newOffer->fill([
            'account_id'           => Auth::user()->getAccountFor('NAU')->getId(),
            'label'                => $request->label,
            'description'          => $request->description,
            'reward'               => $request->reward,
            'start_date'           => $request->start_date,
            'start_time'           => $request->start_time,
            'finish_date'          => $request->finish_date,
            'finish_time'          => $request->finish_time,
            'country'              => $request->country,
            'city'                 => $request->city,
            'category_id'          => null, // $categories->findByName($request->category);
            'max_count'            => $request->max_count,
            'max_for_user'         => $request->max_for_user,
            'max_per_day'          => $request->max_per_day,
            'max_for_user_per_day' => $request->max_for_user_per_day,
            'user_level_min'       => $request->user_level_min,
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
            'radius'               => $request->radius
        ]);

        //todo @coreservise call with $newOffer

        return \response()->render('empty', ['msg' => trans('msg.offer.creating')]);
    }

    /**
     * Get offer full info(for Advert) by it uuid
     * @param string $offerUuid
     * @return Response
     */
    public function show(string $offerUuid): Response
    {
        $offer = new Offer();
        $offer->find($offerUuid);

        if ($offer->account->owner === Auth::user()) {
            return \response()->render('offer.show', [
                'data' => $offer->fresh()
            ]);
        }
        return \response()->error('404', trans('errors.offer_not_found'));
    }

    /**
     * Get offer short info(for User) by it uuid
     * @param string $offerUuid
     * @return Response
     */
    public function view(string $offerUuid) : Response
    {
        $offer = new Offer();
        $offer->find($offerUuid);
        return \response()->render('offer.view', [
            'data' => [
                'label' => $offer->getLabel(),
                'description' => $offer->getDescription()
            ]
        ]);
    }

    /**
     * Offer redemption
     * @param string $offerUuid
     */
    public function activate(string $offerUuid)
    {
        $redemption = new Redemption();
        $redemption->fill([
            'offer_id' => $offerUuid,
            'user_id' => Auth::user()->getId(),
        ]);

        //todo @coreservise call with redemption method

        return \response()->render('empty',['msg' => trans('msg.offer.activating')]);
    }

}
