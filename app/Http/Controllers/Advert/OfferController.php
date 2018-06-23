<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use App\Helpers\FormRequest;
use App\Http\Requests\Advert;
use App\Http\Requests\Offer\UpdateStatusRequest;
use App\Models\Contracts\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Presenters\OfferPresenter;
use App\Repositories\OfferRepository;
use App\Services\OfferReservation;
use App\Services\WeekDaysService;
use App\Traits\FractalToIlluminatePagination;
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
    use FractalToIlluminatePagination;

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

        $repository = $this->offerRepository->setPresenter(OfferPresenter::class);

        $offersData = $repository->with('timeframes')
            ->scopeAccount($this->user()->getAccountForNau())
            ->paginateWithoutGlobalScopes();

        $responseData = $this->getIlluminatePagination($offersData)
            ->toArray();

        $responseData['place'] = $this->user()->place;

        return \response()->render('advert.offer.index', $responseData);
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
        $this->authorize('offers.create');

        $attributes = $request->all();
        $account    = $this->user()->getAccountForNau();

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
        $offer = $this->offerRepository->findWithoutGlobalScopes($offerUuid);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $this->authorize('my.offer.show', $offer);

        $presenter = new OfferPresenter($this->auth, $this->weekDaysService);
        $offerData = array_get($presenter->present($offer), 'data');

        return \response()->render('advert.offer.show', $offerData);
    }

    /**
     * Delete offer (for Advert) by uuid
     *
     * @param string $offerUuid
     *
     * @return Response
     * @return HttpException
     */
    public function destroy(string $offerUuid): Response
    {
        $offer = $this->offerRepository->findByIdAndAccountId($offerUuid,
            $this->user()->getAccountFor(Currency::NAU)->id);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $this->authorize('offers.delete', $offer);

        $offer->delete();

        return \response(null, 204);
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
        $offer   = $this->offerRepository->findWithoutGlobalScopes($offerUuid);
        $account = $this->user()->getAccountForNau();

        $this->authorize('offers.update', $offer);

        $status     = $request->get('status');
        $attributes = ['status' => $status];

        if (Offer::STATUS_ACTIVE == $status) {
            $attributes['status'] = $this->inquireStatus($account, $offer->getReward(), $offer->getReserved());
        }

        $this->offerRepository->update($attributes, $offer->getId());

        if (!request()->wantsJson()) {
            return \response()->redirectTo(route('advert.offers.index'));
        }
        return $this->acceptedResponse('advert.offers.show', $offerUuid);
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
    public function edit(string $offerUuid): Response
    {
        $offer = $this->offerRepository->findWithoutGlobalScopes($offerUuid);

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $offer->load('timeframes');

        $this->authorize('my.offer.show', $offer);

        $presenter = new OfferPresenter($this->auth, $this->weekDaysService);
        $offerData = array_get($presenter->present($offer), 'data');

        return \response()->render('advert.offer.edit', $offerData);
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
        $offer   = $this->offerRepository->findWithoutGlobalScopes($offerUuid);
        $account = $this->user()->getAccountForNau();

        $this->authorize('offers.update', $offer);

        $attributes = $request->all();

        $attributes['status'] = $this->inquireStatus($account, $attributes['reward'], $attributes['reserved']);

        $this->moderateAttributes($attributes);

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

    /**
     * @param array $attributes
     *
     * @return void
     */
    private function moderateAttributes(array $attributes)
    {
        if (false === $this->user()->can('offers.manage_featured_options')) {
            array_forget($attributes, Offer::featuredOptions());
        }
    }
}
