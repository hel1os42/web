<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Models\Contracts\Currency;
use App\Repositories\OfferRepository;
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
     * @throws \LogicException
     */
    public function index(): Response
    {
        $offers       = $this->auth->user()->getAccountFor(Currency::NAU)->offers();
        $paginator    = $offers->paginate();
        $data         = $paginator->toArray();
        $data['data'] = $this->weekDaysService->convertOffersCollection($paginator->getCollection());

        return \response()->render('advert.offer.index', $data);
    }

    /**
     * Get the form/json data for creating a new offer.
     * @return Response
     */
    public function create(): Response
    {
        return \response()->render('advert.offer.create',
            FormRequest::preFilledFormRequest(Advert\OfferRequest::class));
    }

    /**
     * Send new offer data to core to store
     *
     * @param  Advert\OfferRequest $request
     *
     * @return Response
     */
    public function store(Advert\OfferRequest $request): Response
    {
        $newOffer = $this->offerRepository->createForAccountOrFail(
            $request->all(),
            $this->auth->user()->getAccountFor(Currency::NAU)
        );

        return \response()->render('advert.offer.store',
            $newOffer->toArray(),
            Response::HTTP_ACCEPTED,
            route('advert.offers.index'));
    }

    /**
     * Get offer full info(for Advert) by it uuid
     *
     * @param string $offerUuid
     *
     * @return Response
     * @throws HttpException
     * @throws \LogicException
     */
    public function show(string $offerUuid): Response
    {
        $offer = $this->offerRepository->findByIdAndOwner($offerUuid, $this->auth->user());
        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }
        $data = $offer->toArray();
        if (array_key_exists('timeframes', $data)) {
            $data['timeframes'] = $this->weekDaysService->convertTimeframesCollection($offer->timeframes);
        }

        return \response()->render('advert.offer.show', $data);
    }
}
