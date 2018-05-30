<?php

namespace App\Http\Controllers\User;

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
        $this->offerRepository = $offerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->weekDaysService = $weekDaysService;

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

        $categoryIds = $this->categoryRepository->scopeQuery(function($query) use($request) {
            return $query->whereIn('id', $request->category_ids)
                ->orWhereIn('parent_id', $request->category_ids)
                ->pluck('id');
        })->all();

        $repository = $this->offerRepository->setPresenter(OfferPresenter::class);

        $paginator = $repository->scopeQuery(function($query) use ($request, $categoryIds) {
            return $query->whereIn('category_id', $categoryIds)
                ->filterByPosition($request->latitude, $request->longitude, $request->radius)
                ->select(Offer::$publicAttributes);
        });

        $offersData = $paginator->paginate($repository->makeModel()->getPerPage());

        return response()->render('user.offer.index', $this->getIlluminatePagination($offersData));
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
