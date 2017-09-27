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
use App\Services\OffersService;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RedemptionController extends Controller
{
    private $offerRepository;
    private $auth;

    public function __construct(OfferRepository $offerRepository, AuthManager $auth)
    {
        $this->offerRepository = $offerRepository;
        $this->auth            = $auth;
    }

    /**
     * @param string $offerId
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function getActivationCode(string $offerId): Response
    {
        $this->validateOffer($offerId);

        $offer = $this->offerRepository->find($offerId);

        /** @var User $user */
        $user = $this->auth->guard()->user();

        $activationCode = $user->activationCodes()->create(['offer_id' => $offer->id]);

        return \response()->render('redemption.code', $activationCode->toArray());
    }

    /**
     * @param string $offerId
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(string $offerId): Response
    {
        $offer = $this->validateOfferAndGetOwn($offerId);

        return \response()->render('redemption.create', ['offer_id' => $offer->id, 'code' => null]);
    }

    /**
     * @param RedemptionRequest $request
     * @param string            $offerId
     * @param OffersService     $offersService
     *
     * @return Response
     * @throws HttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function redemption(RedemptionRequest $request, string $offerId, OffersService $offersService): Response
    {
        $offer = $this->validateOfferAndGetOwn($offerId);

        $redemption = $offersService->redeem($offer, $request->code);

        return \response()->render(
            'redemption.redeem',
            $redemption->toArray(),
            Response::HTTP_CREATED,
            route('redemption.show', ['offerId' => $offer->getId(), 'rid' => $redemption->getId()])
        );
    }

    /**
     * @param string $offerId
     * @param string $rid
     *
     * @return Response
     * @throws HttpException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function show(string $offerId, string $rid): Response
    {
        $offer = $this->validateOfferAndGetOwn($offerId);

        return \response()->render('redemption.show', $offer->redemptions()->findOrFail($rid)->toArray());
    }

    /**
     * @param $offerId
     *
     * @return void
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

    private function validateOfferAndGetOwn(string $offerId): Offer
    {
        $this->validateOffer($offerId);

        $offer = $this->offerRepository->find($offerId);

        if (!$offer->isOwner($this->auth->guard()->user())) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        return $offer;
    }
}
