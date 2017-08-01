<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        //check is this offer have active status
        $offer = Offer::findOrFail($offerUuid);
        return \response()->render('user.offer.show', [
            'data' => $offer->isOwner(\auth()->user()) ? $offer : $offer->setVisible(Offer::$visibleFields)
        ]);
    }

    /**
     *  Get activation code for Offer
     * @param string $offerUuid
     */
    public function getActivationCode(string $offerUuid)
    {
        $offer = new Offer();
        $offer->findOrFail($offerUuid);
        //If offer exist search or create activation code model obj and save it
        // create table activation_codes with offer uuid, user uuid, expire_date

        return \response()->render('empty', [
            'data' =>
                [
                    'code' => 'AKS7'
                ]
        ], Response::HTTP_CREATED);
    }
}
