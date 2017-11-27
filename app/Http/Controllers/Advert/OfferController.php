<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Http\Requests\Offer\UpdateStatusRequest;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Repositories\OfferRepository;
use App\Services\OfferReservation;
use App\Services\WeekDaysService;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class OfferController
 * @package App\Http\Controllers\Advert
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferController extends Controller
{
    private $offerRepository;
    private $weekDaysService;
    private $reservationService;

    public function __construct(
        OfferRepository $offerRepository,
        AuthManager $authManager,
        WeekDaysService $weekDaysService,
        OfferReservation $reservationService
    ) {
        $this->offerRepository    = $offerRepository;
        $this->weekDaysService    = $weekDaysService;
        $this->reservationService = $reservationService;

        parent::__construct($authManager);
    }

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('my.offers.list');
        $account      = $this->auth->user()->getAccountForNau();
        $paginator    = $this->offerRepository
            ->scopeAccount($account)
            ->paginate();
        $data         = $paginator->toArray();
        $data['data'] = $this->weekDaysService->convertOffersCollection($paginator->getCollection());

        return \response()->render('advert.offer.index', $data);
    }

    /**
     * Get the form/json data for creating a new offer.
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(): Response
    {
        $this->authorize('offers.create');

        return \response()->render('advert.offer.create',
            FormRequest::preFilledFormRequest(Advert\OfferRequest::class));
    }

    /**
     * Send new offer data to core to store
     *
     * @param Advert\OfferRequest $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(Advert\OfferRequest $request): Response
    {
        $this->authorize('offers.store');

        $attributes = $request->all();
        $account    = $this->auth->user()->getAccountForNau();

        $attributes['status'] = $this->inquireStatus($account, $attributes['reward'], $attributes['reserved']);

        $newOffer = $this->offerRepository->createForAccountOrFail(
            $attributes,
            $account
        );

        return \response()->render('advert.offer.store',
            null,
            Response::HTTP_ACCEPTED,
            route('advert.offers.show', $newOffer->id));
    }

    /**
     * Get offer full info(for Advert) by it uuid
     *
     * @param string $offerUuid
     *
     * @return Response
     * @throws HttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $offerUuid): Response
    {
        $offer = $this->offerRepository->find($offerUuid);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }
        $data = $offer->toArray();
        if (array_key_exists('timeframes', $data)) {
            $data['timeframes'] = $this->weekDaysService->convertTimeframesCollection($offer->timeframes);
        }

        $this->authorize('my.offer.show', $offer);

        return \response()->render('advert.offer.show', $data);
    }

    /**
     * @param UpdateStatusRequest $request
     * @param string              $offerUuid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function updateStatus(UpdateStatusRequest $request, string $offerUuid): Response
    {
        $offer   = $this->offerRepository->find($offerUuid);
        $account = $this->auth->user()->getAccountForNau();

        $this->authorize('offers.update', $offer);

        $status     = $request->get('status');
        $attributes = ['status' => $status];

        if (Offer::STATUS_ACTIVE == $status) {
            $attributes['status'] = $this->inquireStatus($account, $offer->getReward(), $offer->getReserved());
        }

        $this->offerRepository->update($attributes, $offer->getId());

        return $this->acceptedResponse('advert.offers.show', $offerUuid);
    }

    /**
     * @param Advert\OfferRequest $request
     * @param string              $offerUuid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function update(Advert\OfferRequest $request, string $offerUuid)
    {
        $offer   = $this->offerRepository->find($offerUuid);
        $account = $this->auth->user()->getAccountForNau();

        $this->authorize('offers.update', $offer);

        $attributes = $request->all();

        $attributes['status'] = $this->inquireStatus($account, $attributes['reward'], $attributes['reserved']);

        $this->offerRepository->update($attributes, $offer->getId());

        return $this->acceptedResponse('advert.offers.show', $offerUuid);
    }

    /**
     * @param Account $account
     * @param float   $reward
     * @param float   $reserved
     *
     * @return string
     */
    private function inquireStatus(Account $account, float $reward, float $reserved)
    {
        return $this->reservationService->isReservable($account, $reward, $reserved)
            ? Offer::STATUS_ACTIVE
            : Offer::STATUS_DEACTIVE;
    }

    /**
     * @param string $route
     * @param string $offerUuid
     *
     * @return Response
     * @throws \LogicException
     */
    private function acceptedResponse(string $route, string $offerUuid)
    {
        $route = route($route, $offerUuid);
        if (request()->wantsJson()) {
            return response()->json(null, 202)->header('Location', $route);
        }

        return response(null, 202)->header('Location', $route);
    }
}
