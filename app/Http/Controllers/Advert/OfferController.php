<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NauModels\Offer;
use App\Http\Requests\Advert;

class OfferController extends Controller
{

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     */
    public function index(): Response
    {
        return \response()->render('advert.offer.index', auth()->user()->getAccountFor(Currency::NAU)->offers()->paginate());
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
     * @param  Advert\OfferRequest $request
     * @return Response
     */
    public function store(Advert\OfferRequest $request): Response
    {
        $newOffer = new Offer();
        $newOffer->account()->associate(auth()->user()->getAccountFor(Currency::NAU));
        $newOffer->fill($request->toArray());
        /*
        $newOffer->status = 'deactive';
        $newOffer->id = 'e60834c2-844e-42d5-84e4-d7136e511ff9';
        $newOffer->save();
        */
        return \response()->render('empty',
            ['data' => $newOffer->toArray(), 'msg' => trans('msg.offer.creating')],
            Response::HTTP_ACCEPTED,
            route('advert.offer'));
    }

    /**
     * Get offer full info(for Advert) by it uuid
     * @param string $offerUuid
     * @return Response
     */
    public function show(string $offerUuid): Response
    {
        $offer = (new Offer())->findOrFail($offerUuid);

        if (auth()->user()->equals($offer->getOwner())) {
            return \response()->render('advert.offer.show', [
                'data' => $offer
            ]);
        }
        return \response()->error(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
    }
}
