<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Models\Contracts\Currency;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferController extends Controller
{
    private $offerRepository;
    private $auth;

    public function __construct(OfferRepository $offerRepository, AuthManager $authManager)
    {
        $this->offerRepository = $offerRepository;
        $this->auth            = $authManager->guard();
    }

    /**
     * Obtain a list of the offers that this user created
     * @return Response
     */
    public function index(): Response
    {
        $offers = $this->auth->user()->getAccountFor(Currency::NAU)->offers();

        return \response()->render('advert.offer.index', $offers->paginate());
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
     */
    public function show(string $offerUuid): Response
    {
        $offer = $this->offerRepository->findByIdAndOwner($offerUuid, $this->auth->user());

        if (null === $offer) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }

        return \response()->render('advert.offer.show', $offer->toArray());
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
