<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use App\Helpers\FormRequest;
use App\Http\Requests\OperatorRequest;
use App\Repositories\OperatorRepository;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class OperatorController
 *
 * @package App\Http\Controllers\Advert
 */
class OperatorController extends Controller
{
    private $operatorRepository;
    private $placeRepository;

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
        $this->authorize('operators.list');
        $place          = $this->placeRepository->skipCriteria()->findByUser($this->user());
        $operators      = $this->operatorRepository->findByPlace($place);
        $result['data'] = $operators->toArray();

        return \response()->render('advert.operator.index', $result);
    }

    /**
     * Get the form/json data for creating a new operator.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function create(): Response
    {
        $this->authorize('operators.create');
        $result = FormRequest::preFilledFormRequest(OperatorRequest::class);

        $result['place_uuid'] = $this->placeRepository->findByUser($this->user())->getId();
        $result['is_active']  = false;

        return \response()->render('advert.operator.create', $result);
    }

    /**
     * Send new offer data to core to store
     *
     * @param OperatorRequest $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(OperatorRequest $request): Response
    {
        $this->authorize('operators.create');

        $attributes  = $request->all();
        $user        = $this->user();
        $place       = $this->placeRepository->findByUser($user);
        $newOperator = $this->operatorRepository
            ->createForPlaceOrFail($attributes, $place);

        $newOperator = $newOperator->fresh()->toArray();

        return \response()->render('advert.operator.show',
            $newOperator,
            Response::HTTP_CREATED,
            route('advert.operators.show', $newOperator['id']));
    }

    /**
     * Get offer full info(for Operator) by it uuid
     *
     * @param string $operatorUuid
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

        $this->authorize('operators.show', $operator);
        $operator = $operator->toArray();

        return \response()->render('advert.operator.show', $operator);
    }

    /**
     * Delete operator (for Advert) by uuid
     *
     * @param string $operatorUuid
     *
     * @return Response
     * @return HttpException
     */
    public function destroy(string $operatorUuid): Response
    {
        $user      = $this->user();
        $placeUuid = $this->placeRepository->findByUser($user)->getId();
        $operator  = $this->operatorRepository->findByIdAndPlaceId($operatorUuid, $placeUuid);

        if (null === $operator) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.operator_not_found'));
        }

        $this->authorize('operators.delete', $operator);

        $operator->delete();

        return \response(null, 204);
    }

    /**
     * Get and edit operator field (for Advert)
     *
     * @param string $operatorUuid
     *
     * @return Response
     * @return HttpException
     */
    public function edit(string $operatorUuid): Response
    {
        $operator = $this->operatorRepository->find($operatorUuid);

        if (null === $operator) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.operator_not_found'));
        }

        $this->authorize('operators.update', $operator);
        $operator = $operator->toArray();

        return \response()->render('advert.operator.edit', $operator);
    }

    /**
     * Upadte operator field (for Advert)
     *
     * @param OperatorRequest $request
     *
     * @return Response
     * @return HttpException
     */
    public function update(OperatorRequest $request): Response
    {
        $placeUuid = $this->placeRepository->findByUser($this->user())->getId();
        $operator  = $this->operatorRepository->findByIdAndPlaceId($request->id, $placeUuid);

        $this->authorize('operators.update', $operator);
        $attributes = request()->all();
        $this->operatorRepository->update($attributes, $operator->getId());
        $operator = $operator->fresh()->toArray();

        return 'PATCH' === $request->method() ?
            \redirect()->back() :
            \response()->render('advert.operator.show', $operator, Response::HTTP_CREATED,
                route('advert.operators.show', $operator));
    }
}
