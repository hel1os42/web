<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NauModels\Offer;

class OfferController extends Controller
{

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     */
    public function index(): Response
    {
        $offers = Offer::accountOffers(auth()->user()->getAccountFor('NAU')->getId())->get();
        return \response()->render('advert.offer.list', [
            'data' => $offers
        ]);
    }

    /**
     * Get the form/json data for creating a new offer.
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('advert.offer.create', [
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
            'account_id'           => (int)auth()->user()->getAccountFor('NAU')->getId(),
            'label'                => $request->label,
            'description'          => $request->description,
            'reward'               => $request->reward,
            'start_date'           => Carbon::parse($request->start_date),
            'start_time'           => Carbon::parse($request->start_time),
            'finish_date'          => Carbon::parse($request->finish_date),
            'finish_time'          => Carbon::parse($request->finish_time),
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
        /*
        $newOffer->status = 'deactive';
        $newOffer->id = 'e60834c2-844e-42d5-84e4-d7136e511ff9';
        $newOffer->save();
        */

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
        $offer = $offer->findOrFail($offerUuid);
        $owner = $offer->getOwner();

        if ($owner !== null && $owner->id === auth()->user()->id) {
            return \response()->render('advert.offer.show', [
                'data' => $offer
            ]);
        }
        return \response()->error('404', trans('errors.offer_not_found'));
    }

}
