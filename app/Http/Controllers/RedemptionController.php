<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 07.08.2017
 * Time: 17:33
 */

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\RedemptionRequest;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Repositories\OfferRepository;
use App\Repositories\RedemptionRepository;
use App\Services\OffersService;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RedemptionController extends Controller
{
    private $offerRepository;

    public function __construct(
        OfferRepository $offerRepository,
        AuthManager $auth
    ) {
        $this->offerRepository = $offerRepository;

        parent::__construct($auth);
    }

    /**
     * Method index redirect to redemptions for User
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $placeUuid = $this->user()->place->id;

        return \response()->redirectTo(route('places.list.redemptions', $placeUuid));
    }

    /**
     * @param string $offerId
     *
     * @return Response
     * @throws HttpException
     */
    public function getActivationCode(string $offerId): Response
    {
        if (!$this->offerRepository->validateOffer($offerId)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $offer = $this->offerRepository->find($offerId);

        $this->authorize('offers.redemption', $offer);

        $activationCode = $this->user()->activationCodes()->with('offer.account.owner')->orderBy('created_at', 'desc')->first();

        if ($activationCode === null || Carbon::now()->subMinute(15) > $activationCode->created_at) {
            $activationCode = $this->user()->activationCodes()->create(['offer_id' => $offer->id]);
        };

        return \response()->render('redemption.code', $activationCode->toArray());
    }

    /**
     * @param string $offerId
     *
     * @return Response
     * @throws HttpException
     */
    public function createFromOffer(string $offerId): Response
    {
        $offer = $this->offerRepository->validateOfferAndGetOwn($offerId);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $this->authorize('offers.redemption', $offer);

        return \response()->render('redemption.create', ['offer_id' => $offer->id, 'code' => null]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $this->authorize('offers.redemption');

        return \response()->render('redemption.create', ['code' => null]);
    }

    /**
     * @param RedemptionRequest $request
     * @param OffersService     $offersService
     *
     * @return Response
     */
    public function store(RedemptionRequest $request, OffersService $offersService): Response
    {
        $code = $request->code;

        if ($request->wantsJson() && config('app.review_stub.code') === $code) {
            return $this->reviewStubStore();
        }

        $activationCode = $offersService->getActivationCodeByCode($code);

        if($activationCode->offer === null) {
            throw new ModelNotFoundException();
        }

        $this->authorize('offers.redemption.confirm', $activationCode->offer);

        $redemption = $offersService->redeemByActivationCode($activationCode);

        return \response()->render(
            'redemption.redeem',
            $redemption->toArray(),
            Response::HTTP_CREATED,
            route('redemptions.show', ['id' => $redemption->getId()])
        );
    }

    private function reviewStubStore()
    {
        return \response()->render(
            'redemption.redeem',
            [],
            Response::HTTP_CREATED,
            route('redemptions.show', ['id' => config('app.review_stub.redemption_id')])
        );
    }

    /**
     * @param RedemptionRequest $request
     * @param string            $offerId
     * @param OffersService     $offersService
     *
     * @return Response
     * @throws HttpException
     */
    public function redemption(RedemptionRequest $request, string $offerId, OffersService $offersService): Response
    {
        $offer = $this->offerRepository->validateOfferAndGetOwn($offerId);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $this->authorize('offers.redemption.confirm', $offer);

        $redemption = $offersService->redeemByOfferAndCode($offer, $request->code);

        return \response()->render(
            'redemption.redeem',
            $redemption->toArray(),
            Response::HTTP_CREATED,
            route('redemption.show', ['offerId' => $offer->getId(), 'rid' => $redemption->getId()])
        );
    }

    /**
     * @param string               $redemptionId
     * @param RedemptionRepository $repository
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function show(string $redemptionId, RedemptionRepository $repository): Response
    {
        if (request()->wantsJson() && config('app.review_stub.redemption_id') === $redemptionId) {
            return $this->reviewStubShow();
        }

        $redemption = $repository->find($redemptionId);

        $this->authorize('offers.redemption.show', $redemption);

        return \response()->render('redemption.show', $redemption->toArray());
    }

    private function reviewStubShow()
    {
        $redemption = (new Redemption())->forceFill([
            'id'    => config('app.review_stub.redemption_id'),
            'offer' => (new Offer)->forceFill(config('app.review_stub.offer')),
        ]);

        return \response()->render('redemption.show', $redemption->toArray());
    }

    /**
     * @param string $offerId
     * @param string $rid
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function showFromOffer(string $offerId, string $rid): Response
    {
        $offer = $this->offerRepository->validateOfferAndGetOwn($offerId);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $redemption = $offer->redemptions()->findOrFail($rid);

        $this->authorize('offers.redemption.show', $redemption);

        return \response()->render('redemption.show', $redemption->toArray());
    }
}
