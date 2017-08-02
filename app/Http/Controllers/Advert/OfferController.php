<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NauModels\Offer;
use App\Http\Requests\Advert;
use App\Models\User;

class OfferController extends Controller
{

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     */
    public function index(): Response
    {
        return \response()->render('advert.offer.index', auth()->user()->getAccountFor('NAU')->offers()->paginate());
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
        $newOffer->account()->assign(auth()->user()->getAccountFor(Currency::NAU));
        $newOffer->fill($request->toArray());
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
        $offer = (new Offer())->findOrFail($offerUuid);

        if (auth()->user()->equals($offer->getOwner())) {
            return \response()->render('advert.offer.show', [
                'data' => $offer
            ]);
        }
        return \response()->error('404', trans('errors.offer_not_found'));
    }

    /**
     * @param Advert\OfferRedemptionRequest $request
     * @return Response
     */
    public function redemption(Advert\OfferRedemptionRequest $request): Response
    {

        //check is current user owner of $request->offer_id and offer exist
        //check is offer have active status, etc
        (new Offer)->redeem(new User());//get user model(from code) and call Offer->redeem(User)

        if ($request->code == 'AKS7') { //check is code valid from activation_codes table
            return \response()->render('empty', ['msg' => trans('msg.offer.activating')]);
        }
        return \response()->error('404', trans('error.bad_activation_code'));
    }
}
