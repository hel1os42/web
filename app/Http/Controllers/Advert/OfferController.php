<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Models\NauModels\Offer;
use App\Repositories\OfferRepository;
use App\Services\OfferReservation;
use App\Services\WeekDaysService;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferController extends Controller
{
    private $offerRepository;
    private $auth;
    private $weekDaysService;

    public function __construct(
        OfferRepository $offerRepository,
        AuthManager $authManager,
        WeekDaysService $weekDaysService
    ) {
        $this->offerRepository = $offerRepository;
        $this->auth            = $authManager->guard();
        $this->weekDaysService = $weekDaysService;
    }

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('index', Offer::class);
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
        $this->authorize('create', Offer::class);

        return \response()->render('advert.offer.create',
            FormRequest::preFilledFormRequest(Advert\OfferRequest::class));
    }

    /**
     * Send new offer data to core to store
     *
     * @param Advert\OfferRequest $request
     * @param OfferReservation    $reservationService
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(Advert\OfferRequest $request, OfferReservation $reservationService): Response
    {
        $this->authorize('store', Offer::class);

        $attributes = $request->all();
        $account    = $this->auth->user()->getAccountForNau();

        $attributes['status'] = $reservationService->isReservable(
            $account,
            $attributes['reward'],
            $attributes['reserved']
        )
            ? Offer::STATUS_ACTIVE
            : Offer::STATUS_DEACTIVE;

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

        $this->authorize('show', $offer);

        return \response()->render('advert.offer.show', $data);
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
        $offer = $this->offerRepository->findByIdAndOwner($offerUuid, $this->auth->user());

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        $offer->delete();

        return \response()->json([],204);
    }
}
