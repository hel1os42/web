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

class RedemptionController extends Controller
{

    /**
     * @param string $offerId
     * @return Response
     */
    public function getActivationCode(string $offerId): Response
    {
        return (new Offer())->findOrFail($offerId) instanceof Offer ?
            \response()->render('redemption.code', [
                'activation_code' => auth()->user()->activationCodes()->create(['offer_id' => $offerId])->getCode()
            ]) :
            \response()->error(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
    }

    /**
     * @param string $offerId
     * @return Response
     */
    public function create(string $offerId): Response
    {
        if (auth()->user()->equals((new Offer())->findOrFail($offerId)->getOwner())) {
            return \response()->render('redemption.create', ['offer_id' => $offerId]);
        }
        return \response()->error(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param RedemptionRequest $request
     * @param string $offerId
     * @return Response
     */
    public function redemption(RedemptionRequest $request, string $offerId): Response
    {
        $activationCode = new ActivationCode();
        $activationCode = $activationCode->findOrFail($activationCode->getIdByCode($request->code));
        if ($activationCode->checkOffer($offerId)) {
            $redemption = (new Redemption())->create([
                'offer_id' => $offerId,
                'user_id'  => $activationCode->user()->get(['id'])
            ]);
            if ($redemption instanceof Redemption) {
                $activationCode->setRedemptionId($redemption->getId())->update();
                return \response()->render(
                    'redemption.redeem',
                    $redemption->toArray(),
                    Response::HTTP_CREATED,
                    route('redemption.show', ['offerId' => $offerId, 'rid' => $redemption->getId()]));
            }
        }
        return \response()->error(Response::HTTP_BAD_REQUEST, 'Can\'t activate offer.');
    }

    /**
     * @param string $offerId
     * @param string $rid
     * @return Response
     */
    public function show(string $offerId, string $rid): Response
    {
        if (auth()->user()->equals((new Offer())->findOrFail($offerId)->getOwner())) {
            return \response()->render('redemption.show', (new Redemption())->findOrFail($rid)->toArray());
        }
        return \response()->error(Response::HTTP_UNAUTHORIZED);
    }
}
