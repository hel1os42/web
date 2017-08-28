<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 07.08.2017
 * Time: 17:33
 */

namespace App\Http\Controllers;

use App\Http\Requests\RedemptionRequest;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use Symfony\Component\HttpFoundation\Response;
use Vinkla\Hashids\Facades\Hashids;

class RedemptionController extends Controller
{

    /**
     * @param string $offerId
     * @return Response
     */
    public function getActivationCode(string $offerId): Response
    {
        return Offer::find($offerId)->first() ?
            \response()->render('redemption.code',
                auth()->user()->activationCodes()->create(['offer_id' => $offerId])->toArray()) :
            \response()->error(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
    }

    /**
     * @param string $offerId
     * @return Response
     */
    public function create(string $offerId): Response
    {
        return Offer::findOrFail($offerId)->isOwner(auth()->user()) ?
            \response()->render('redemption.create', ['offer_id' => $offerId, 'code' => null]) :
            \response()->error(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param RedemptionRequest $request
     * @param string $offerId
     * @return Response
     */
    public function redemption(RedemptionRequest $request, string $offerId): Response
    {
        $offer = Offer::findOrFail($offerId);

        if ($offer->isOwner(auth()->user())) {
            $redemption = $offer->redeem($request->code);
            return \response()->render(
                'redemption.redeem',
                $redemption->toArray(),
                Response::HTTP_CREATED,
                route('redemption.show', ['offerId' => $offer->getId(), 'rid' => $redemption->getId()])
            );
        }
        return \response()->error(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param string $offerId
     * @param string $rid
     * @return Response
     */
    public function show(string $offerId, string $rid): Response
    {
        $offer = Offer::findOrFail($offerId);
        return $offer->isOwner(auth()->user()) ?
            \response()->render('redemption.show', $offer->redemptions()->findOrFail($rid)->toArray()) :
            \response()->error(Response::HTTP_UNAUTHORIZED);
    }
}
