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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function getActivationCode(string $offerId): Response
    {
        $this->authorize('redemption.code');

        $this->validateOffer($offerId);

        $offer = $this->offerRepository->find($offerId);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function createFromOffer(string $offerId): Response
    {
        $this->authorize('redemption.create.from_offer');

        $offer = $this->validateOfferAndGetOwn($offerId);

        return \response()->render('redemption.create', ['offer_id' => $offer->id, 'code' => null]);
    }

    /**
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function create(): Response
    {
        $this->authorize('redemption.create');

        return \response()->render('redemption.create', ['code' => null]);
    }

    /**
     * @param RedemptionRequest $request
     * @param OffersService     $offersService
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(RedemptionRequest $request, OffersService $offersService): Response
    {
        $this->authorize('redemption.store');

        $code = $request->code;

        $redemption = $offersService->redeemByOwnerAndCode($this->auth->user(), $code);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function redemption(RedemptionRequest $request, string $offerId, OffersService $offersService): Response
    {
        $this->authorize('redemption.redeem');

        $offer = $this->validateOfferAndGetOwn($offerId);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $redemptionId, RedemptionRepository $repository): Response
    {
        $redemption = $repository->find($redemptionId);

        $this->authorize('redemption.show', $redemption);

        return \response()->render('redemption.show', $redemption->toArray());
    }

    /**
     * @param string $offerId
     * @param string $rid
     *
     * @return Response
     * @throws HttpException
     * @throws ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function showFromOffer(string $offerId, string $rid): Response
    {
        $this->authorize('redemption.show.from_offer');

        $offer = $this->validateOfferAndGetOwn($offerId);

        return \response()->render('redemption.show', $offer->redemptions()->findOrFail($rid)->toArray());
    }

    /**
     * @param string $offerId
     *
     * @throws HttpException
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

    /**
     * @param string $offerId
     *
     * @return Offer
     * @throws HttpException
     */
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
