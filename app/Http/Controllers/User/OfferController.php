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
        return response()->render('user.offer.index',
            Offer::filterByCategory($request->category)
            ->filterByPosition($request->latitude, $request->longitude, $request->radius)
            ->select(Offer::$publicAttributes)
            ->paginate()
        );
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
        return \response()->render('user.offer.show', $offer->isOwner(\auth()->user()) ? $offer->toArray() : $offer->setVisible(Offer::$publicAttributes)->toArray());
    }
}
