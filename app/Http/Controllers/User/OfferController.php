<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OfferRequest;
use App\Models\NauModels\Offer;
use App\Repositories\OfferRepository;
use App\Services\WeekDaysService;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

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
     * List offers
     *
     * @param OfferRequest $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function index(OfferRequest $request): Response
    {
        $offers       = $this->offerRepository
            ->getActiveByCategoriesAndPosition($request->category_ids,
                $request->latitude, $request->longitude, $request->radius);
        $paginator    = $offers->select(Offer::$publicAttributes)->paginate();
        $data         = $paginator->toArray();
        $data['data'] = $this->weekDaysService->convertOffersCollection($paginator->getCollection());

        return response()->render('user.offer.index', $data);
    }

    /**
     * Get offer short info(for User) by it uuid
     *
     * @param string $offerUuid
     *
     * @return Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \LogicException
     */
    public function show(string $offerUuid): Response
    {
        //check is this offer have active status
        $offer = $this->offerRepository->findActiveByIdOrFail($offerUuid);

        if ($offer->isOwner($this->auth->user())) {
            $offer->setVisible(Offer::$publicAttributes);
        }
        $data = $offer->toArray();
        if (array_key_exists('timeframes', $data)) {
            $data['timeframes'] = $this->weekDaysService->convertTimeframesCollection($offer->timeframes);
        }
        return \response()->render('user.offer.show', $data);
    }
}
