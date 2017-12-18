<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Http\Requests\Offer\UpdateStatusRequest;
use App\Models\NauModels\Offer;
use App\Repositories\OperatorRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\Contracts\Currency;

/**
 * Class OperatorController
 *
 * @package App\Http\Controllers\Advert
 */
class OperatorController extends Controller
{
    private $operatorRepository;

    public function __construct(
        OperatorRepository $operatorRepository,
        AuthManager $authManager
    ) {
        $this->operatorRepository = $operatorRepository;

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
        //$this->authorize('');
        //$account      = $this->auth->user()->getAccountForNau();

        $operators    = $this->operatorRepository->all();
        $data['data'] = $operators->toArray();

        return \response()->render('advert.operator.index', $data);
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
        $offer = $this->offerRepository->findWithoutGlobalScopes($offerUuid);

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
            $this->auth->user()
                       ->getAccountFor(Currency::NAU)
                ->id);

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
        $offer   = $this->offerRepository->findWithoutGlobalScopes($offerUuid);
        $account = $this->auth->user()->getAccountForNau();

        $this->authorize('offers.update', $offer);

        $attributes = $request->all();

        $attributes['status'] = $this->inquireStatus($account, $attributes['reward'], $attributes['reserved']);

        $this->offerRepository->update($attributes, $offer->getId());

        return $this->acceptedResponse('advert.offers.show', $offerUuid);
    }
}
