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
use App\Models\User;
use App\Repositories\OfferRepository;
use App\Repositories\RedemptionRepository;
use App\Services\OffersService;
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
     * @param string $offerId
     *
     * @return Response
     * @throws HttpException
     */
    public function getActivationCode(string $offerId): Response
    {
        $this->validateOffer($offerId);

        $offer = $this->offerRepository->find($offerId);

        $this->authorize('offers.redemption', $offer);

        /** @var User $user */
        $user = $this->auth->user();

        $activationCode = $user->activationCodes()->create(['offer_id' => $offer->id]);

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
        $offer = $this->validateOfferAndGetOwn($offerId);

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

        $activationCode = $offersService->getActivationCodeByCode($code);

        $this->authorize('offers.redemption.confirm', $activationCode->offer);

        $redemption = $offersService->redeemByActivationCode($activationCode);

        return \response()->render(
            'redemption.redeem',
            $redemption->toArray(),
            Response::HTTP_CREATED,
            route('redemptions.show', ['id' => $redemption->getId()])
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
        $offer = $this->validateOfferAndGetOwn($offerId);

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
        $redemption = $repository->find($redemptionId);

        $this->authorize('offers.redemption.show', $redemption);

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
        $offer = $this->validateOfferAndGetOwn($offerId);

        $redemption = $offer->redemptions()->findOrFail($rid);

        $this->authorize('offers.redemption.show', $redemption);

        return \response()->render('redemption.show', $redemption->toArray());
    }

    /**
     * @param string $offerId
     */
    private function validateOffer(string $offerId): void
    {
        $validator = $this->getValidationFactory()
                          ->make(['offerId' => $offerId],
                              [
                                  'offerId' => sprintf('string|regex:%s|exists:pgsql_nau.offer,id',
                                      Constants::UUID_REGEX)
                              ]);

        if ($validator->fails()) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }
    }

    private function validateOfferAndGetOwn(string $offerId): Offer
    {
        $this->validateOffer($offerId);

        $offer = $this->offerRepository->find($offerId);

        if (!$offer->isOwner($this->auth->user())) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        return $offer;
    }
}
