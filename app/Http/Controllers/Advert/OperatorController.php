<?php

namespace App\Http\Controllers\Advert;

use App\Helpers\FormRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Advert;
use App\Http\Requests\Offer\UpdateStatusRequest;
use App\Http\Requests\OperatorRequest;
use App\Models\NauModels\Offer;
use App\Repositories\OperatorRepository;
use App\Repositories\PlaceRepository;
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
        PlaceRepository $placeRepository,
        AuthManager $authManager
    ) {
        $this->operatorRepository = $operatorRepository;
        $this->placeRepository    = $placeRepository;

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

        $operators      = $this->operatorRepository->all();
        $result['data'] = $operators->toArray();

        return \response()->render('advert.operator.index', $result);
    }

    /**
     * Get the form/json data for creating a new operator.
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(): Response
    {
        //$this->authorize('operators.create');

        return \response()->render('advert.operator.create',
            FormRequest::preFilledFormRequest(OperatorRequest::class));
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
    public function store(OperatorRequest $request): Response
    {
        //$this->authorize('offers.create');

        $attributes = $request->all();

        $user = $this->auth->user();

        $place = $this->placeRepository->findByUser($user);

        $newOperator = $this->operatorRepository
            ->createForPlaceOrFail($attributes, $place)
            ->first();

        $result['data'] = $newOperator->toArray();
        return \response()->render('advert.operator.show',
            $result,
            Response::HTTP_ACCEPTED,
            route('advert.operators.show', $newOperator->id));
    }

    /**
     * Get offer full info(for Operator) by it uuid
     *
     * @param string $OperatorUuid
     *
     * @return Response
     * @throws HttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $operatorUuid): Response
    {
        $operator = $this->operatorRepository->find($operatorUuid);

        if (null === $operator) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.operator_not_found'));
        }
        $result['data'] = $operator->toArray();

        //$this->authorize('advert.operators.show', $offer);

        return \response()->render('advert.operator.show', $result);
    }
}
