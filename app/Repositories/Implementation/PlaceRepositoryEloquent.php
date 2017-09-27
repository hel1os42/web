<?php

namespace App\Repositories\Implementation;

use App\Models\Place;
use App\Models\User;
use App\Repositories\PlaceRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PlaceRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Place $model
 */
class PlaceRepositoryEloquent extends BaseRepository implements PlaceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Place::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    protected function applyCriteria()
    {
        parent::applyCriteria();
        $this->model->without('offers');
    }

    public function createForUser(array $attributes, User $user): Place
    {
        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $model = $this->model->newInstance($attributes);
        $model->user()->associate($user);
        $model->save();
        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    public function findByUser(User $user): ?Place
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byUser($user)->first();
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
    public function getByCategoriesAndPosition(array $categoryIds, float $latitude, float $longitude, int $radius): Builder
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->filterByCategories($categoryIds)->filterByPosition($latitude, $longitude, $radius);
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function countByUser(User $user): int
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->model->byUser($user)->count();
        $this->resetModel();

        return $result;
    }

    public function existsByUser(User $user): bool
    {
        $this->applyCriteria();
        $this->applyScope();
        $result = $this->countByUser($user) > 0;
        $this->resetModel();

        return $result;
    }
}
