<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OfferRequest;
use App\Models\NauModels\Offer;
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
     * List offers
     *
     * @param OfferRequest $request
     *
     * @return Response
     * @throws \LogicException
     */
    public function index(OfferRequest $request): Response
    {
        $offers = $this->offerRepository
            ->getActiveByCategoriesAndPosition($request->category_ids,
                $request->latitude, $request->longitude, $request->radius);

        return response()->render('user.offer.index', $offers->select(Offer::$publicAttributes)->paginate());
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

        return \response()->render('user.offer.show', $offer->toArray());
    }
}
