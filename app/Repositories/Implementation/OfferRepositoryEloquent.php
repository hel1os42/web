<?php

namespace App\Repositories\Implementation;

use App\Exceptions\Exception;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Repositories\Criteria\MappableRequestCriteria;
use App\Repositories\OfferRepository;
use App\Repositories\TimeframeRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Validator\Contracts\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class OfferRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Offer $model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferRepositoryEloquent extends BaseRepository implements OfferRepository
{
    protected $timeframeRepository;
    protected $reservationService;

    protected $fieldSearchable = [
        'status'      => '=',
        'start_date'  => '<=',
        'finish_date' => '>=',
    ];

    public function __construct(
        Application $app,
        TimeframeRepository $timeframeRepository
    ) {
        $this->timeframeRepository = $timeframeRepository;
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Offer::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(MappableRequestCriteria::class));
    }

    public function createForAccountOrFail(array $attributes, Account $account): Offer
    {
        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $model = $this->model->newInstance($attributes);
        $model->account()->associate($account);

        if (!$model->save()) {
            throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, "Cannot save your offer.");
        }

        $this->timeframeRepository->createManyForOffer($attributes['timeframes'], $model);

        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * @param array      $categoryIds
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null   $radius
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getActiveByCategoriesAndPosition(
        array $categoryIds,
        ?float $latitude,
        ?float $longitude,
        ?int $radius
    ): Builder {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model
            ->active()
            ->filterByCategories($categoryIds)
            ->filterByPosition($latitude, $longitude, $radius);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param string $identity
     *
     * @return Offer
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findActiveByIdOrFail(string $identity): Offer
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->active()->findOrFail($identity);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param Account $account
     *
     * @return OfferRepository
     */
    public function scopeAccount(Account $account): OfferRepository
    {
        return $this->scopeQuery(
            function ($builder) use ($account) {
                return $builder->accountOffers($account->getId());
            }
        );
    }

    /**
     * @param string $offerId
     * @param int $accountId
     *
     * @return Offer|null
     */
    public function findByIdAndOwner(string $offerId, int $accountId): ?Offer
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->where([
            'acc_id' => $accountId
        ])->find($offerId);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param array  $attributes
     * @param string $offerId
     *
     * @return Offer
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(array $attributes, $offerId): Offer
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->setId($offerId)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->find($offerId);

        if (!$model->update($attributes)) {
            throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, "Cannot update your offer.");
        }

        if (array_key_exists('timeframes', $attributes)) {
            $this->timeframeRepository->replaceManyForOffer($attributes['timeframes'], $model);
        }

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }
}
