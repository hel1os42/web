<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OfferRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Models\NauModels\Offer;

class OfferController extends Controller
{

    /**
     * List offers
     * @param OfferRequest $request
     * @return Response
     */
    public function index(OfferRequest $request): Response
    {
        $offers = Offer::filterByCategory($request->category)
            ->filterByPosition($request->latitude, $request->longitude, $request->radius)
            ->select('name','descr')
            ->paginate()
            ->makeVisible(Offer::$publicAttributes);
        return response()->render('user.offer.index', $offers);
    }

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
            'data' => $offer->isOwner(\auth()->user()) ? $offer : $offer->setVisible(Offer::$publicAttributes)
        ]);
    }
}
