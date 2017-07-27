<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NauModels\Offer;

class OfferController extends Controller
{
    /**
     * Get offer short info(for User) by it uuid
     * @param string $offerUuid
     * @return Response
     */
    public function show(string $offerUuid): Response
    {
        $offer = new Offer();
        $offer->findOrFail($offerUuid);
        return \response()->render('user.offer.show', [
            'data' => (object)[
                'label'       => $offer->getLabel(),
                'description' => $offer->getDescription()
            ]
        ]);
    }

    /**
     * Offer redemption
     * @param string $offerUuid
     */
    public function redemption(string $offerUuid)
    {
        $offer = new Offer();
        $offer->findOrFail($offerUuid);
        $offer->redeem(auth()->user());

        return \response()->render('empty', ['msg' => trans('msg.offer.activating')], Response::HTTP_CREATED);
    }

}
