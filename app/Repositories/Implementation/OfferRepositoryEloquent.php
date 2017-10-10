<?php

namespace App\Repositories\Implementation;

use App\Models\Contracts\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\User;
use App\Repositories\OfferRepository;
use App\Services\WeekDaysService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Validator\Contracts\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class OfferRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Offer $model
 */
class OfferRepositoryEloquent extends BaseRepository implements OfferRepository
{
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
        $this->pushCriteria(app(RequestCriteria::class));
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

        $model->timeframes()->createMany($this->replaceTimeframesWeekdaysByDays($attributes['timeframes']));

        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    public function findByIdAndOwner(string $identity, User $user): ?Offer
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->where([
            'acc_id' => $user->getAccountFor(Currency::NAU)->id
        ])->find($identity);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * @param array $categoryIds
     * @param float $latitude
     * @param float $longitude
     * @param int   $radius
     *
     * @return Builder
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getActiveByCategoriesAndPosition(
        array $categoryIds,
        float $latitude,
        float $longitude,
        int $radius
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

    public function findActiveByIdOrFail(string $identity): Offer
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->active()->findOrFail($identity);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Replaces each timeframe days value: instead of an array we store in "days" its binary representation.
     *
     * @param array $timeframes
     *
     * @return array
     */
    protected function replaceTimeframesWeekdaysByDays(array $timeframes): array
    {
        foreach ($timeframes as $key => $timeframe) {
            $timeframes[$key]['days'] = app(WeekDaysService::class)->weekDaysToDays($timeframe['days']);
        }

        return $timeframes;
    }
}
