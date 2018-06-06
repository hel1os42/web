<?php

namespace App\Http\Controllers\User;

use App\Criteria\Offer\CategoryCriteria;
use App\Criteria\Offer\FeaturedCriteria;
use App\Criteria\Offer\PositionCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\OfferRequest;
use App\Models\NauModels\Offer;
use App\Presenters\OfferPresenter;
use App\Repositories\CategoryRepository;
use App\Repositories\OfferRepository;
use App\Services\WeekDaysService;
use App\Traits\FractalToIlluminatePagination;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{
    use FractalToIlluminatePagination;

    private $offerRepository;
    private $weekDaysService;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(
        OfferRepository $offerRepository,
        CategoryRepository $categoryRepository,
        AuthManager $authManager,
        WeekDaysService $weekDaysService
    ) {
        $this->offerRepository    = $offerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->weekDaysService    = $weekDaysService;

        parent::__construct($authManager);
    }

    /**
     * List offers
     *
     * @param OfferRequest $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(OfferRequest $request): Response
    {
        $this->authorize('offers.list');

        $offerRepository = $this->getOfferRepository($request);

        $offersData = $offerRepository->paginate($offerRepository->makeModel()->getPerPage());

        return response()->render('user.offer.index', $this->getIlluminatePagination($offersData));
    }

    /**
     * @param OfferRequest $request
     *
     * @return OfferRepository
     */
    private function getOfferRepository(OfferRequest $request): OfferRepository
    {
        $repository = $this->offerRepository->setPresenter(OfferPresenter::class);

        $latitude  = $request->latitude;
        $longitude = $request->longitude;
        $radius    = $request->radius;

        $repository->pushCriteria(new PositionCriteria($latitude, $longitude, $radius));

        if (true === (bool)$request->featured) {
            $repository->pushCriteria(new FeaturedCriteria());
        }

        $requestedCategoryIds = $request->get('category_ids', []);

        if (count($requestedCategoryIds)) {
            $repository->pushCriteria(new CategoryCriteria($this->categoryRepository, $requestedCategoryIds));
        }

        $visibleFields = array_merge(Offer::$publicAttributes, ['acc_id']);

        $repository->visible($visibleFields);

        $repository->scopeQuery(function ($query) use ($latitude, $longitude, $visibleFields) {
            return $query
                ->orderByPosition($latitude, $longitude)
                ->select($visibleFields);
        });

        return $repository;
    }

    /**
     * Get offer short info(for User) by it uuid
     *
     * @param string $offerUuid
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $offerUuid): Response
    {
        $offer = $this->offerRepository->find($offerUuid);

        $this->authorize('offers.show', $offer);

        if ($offer->isOwner($this->user())) {
            $offer->setVisible(Offer::$publicAttributes);
        }

        $presenter = new OfferPresenter($this->auth, $this->weekDaysService);
        $offerData = array_get($presenter->present($offer), 'data');

        return \response()->render('user.offer.show', $offerData);
    }
}
