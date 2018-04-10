<?php

namespace App\Repositories\Implementation;

use App\Helpers\Constants;
use App\Models\Category;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Repositories\CategoryRepository;
use App\Services\Criteria\MappableRequestCriteria;
use App\Repositories\OfferRepository;
use App\Repositories\TimeframeRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
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
    use ValidatesRequests;

    protected $timeframeRepository;
    protected $categoryRepository;
    protected $reservationService;

    protected $fieldSearchable = [
        'status'      => '=',
        'start_date'  => '<=',
        'finish_date' => '>=',
        'updated_at',
    ];

    public function __construct(
        Application $app,
        TimeframeRepository $timeframeRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->timeframeRepository = $timeframeRepository;
        $this->categoryRepository  = $categoryRepository;
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

        $model->offerData->fill($attributes)
                         ->setOwnerId($account->getOwner()->getId())
                         ->save();

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
     * @throws \InvalidArgumentException
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
            ->whereIn('category_id', $this->getChildCategoryIds($categoryIds))
            ->filterByPosition($latitude, $longitude, $radius);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    private function getChildCategoryIds(array $categoryIds = []): array
    {
        $result = [];

        $this->categoryRepository->skipCriteria();

        foreach ($categoryIds as $categoryId) {
            $result[] = $categoryId;

            /** @var Category $category */
            try {
                $category = $this->categoryRepository->find($categoryId);
            } catch (ModelNotFoundException $exception) {
                continue;
            }

            if ($category->children()->count() > 0) {
                $result = array_merge($result, $category->children()->pluck('id')->toArray());
            }
        }

        return $result;
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
     * @param int    $accountId
     *
     * @return Offer|null
     * @throws \InvalidArgumentException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByIdAndAccountId(string $offerId, int $accountId): ?Offer
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->builderWithoutGlobalScopes()->where([
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
     * @throws HttpException
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

        $model = $this->builderWithoutGlobalScopes()
                      ->find($offerId);

        if (!$model->update($attributes)) {
            throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, "Cannot update your offer.");
        }

        $model->offerData->fill($attributes)->save();
        if (array_key_exists('timeframes', $attributes)) {
            $this->timeframeRepository->replaceManyForOffer($attributes['timeframes'], $model);
        }

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * @param string $offerId
     * @param array  $columns
     *
     * @return Offer
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findWithoutGlobalScopes(string $offerId, array $columns = ['*']): Offer
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->builderWithoutGlobalScopes()
                      ->findOrFail($offerId, $columns);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param null   $limit
     * @param array  $columns
     * @param string $method
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function paginateWithoutGlobalScopes($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit   = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->builderWithoutGlobalScopes()
                        ->withCount('redemptions')
                        ->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    protected function builderWithoutGlobalScopes(): Builder
    {
        return $this->model->withoutGlobalScopes([Offer::statusActiveScope(), Offer::dateActualScope()]);
    }

    /**
     * @return OfferRepository
     */
    public function withoutGlobalScopes(): OfferRepository
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->builderWithoutGlobalScopes();

        return $this;
    }


    /**
     * @param string $offerId
     */
    public function validateOffer(string $offerId): void
    {
        $validator = $this->getValidationFactory()
            ->make(['offerId' => $offerId],
                [
                    'offerId' => sprintf('string|regex:%s|exists:pgsql_nau.offer,id',
                        Constants::UUID_REGEX)
                ]);

        if ($validator->fails()) {
            throw new HttpException(Response::HTTP_NOT_FOUND, trans('errors.offer_not_found'));
        }
    }

    public function validateOfferAndGetOwn(string $offerId): Offer
    {
        $this->validateOffer($offerId);

        $offer = $this->find($offerId);

        if (!$offer->isOwner(Auth::user())) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }

        return $offer;
    }
}
