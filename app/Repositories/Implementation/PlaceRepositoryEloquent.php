<?php

namespace App\Repositories\Implementation;

use App\Http\Exceptions\InternalServerErrorException;
use App\Models\Place;
use App\Models\User;
use App\Repositories\PlaceRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class PlaceRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Place $model
 */
class PlaceRepositoryEloquent extends BaseRepository implements PlaceRepository
{
    protected $fieldSearchable = ['name' => 'like', 'description' => 'like'];

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

    public function createForUserOrFail(array $attributes, User $user, array $specsIds, array $tagsIds): Place
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

        if (array_key_exists('retail_types', $attributes) && count($attributes['retail_types']) > 0) {
            $categories = array_merge([$attributes['category']], $attributes['retail_types']);
            $model->categories()->sync($categories);
        }

        if (count($tagsIds) > 0) {
            $model->tags()->sync($tagsIds);
        }

        if (count($specsIds) > 0) {
            $model->specialities()->sync($specsIds);
        }

        $this->resetModel();

        if (!$model->exists) {
            logger()->error('cannot save place', $attributes);
            throw new InternalServerErrorException('Cannot save place');
        }

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * @param User $user
     *
     * @return Place
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByUser(User $user): Place
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->byUser($user)->firstOrFail();
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
            ->filterByActiveOffersAvailability()
            ->filterByCategories($categoryIds)
            ->filterByPosition($latitude, $longitude, $radius)
            ->orderByPosition($latitude, $longitude);
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

    /**
     * @param array $attributes
     * @param       $placeId
     * @param array $specsIds
     * @param array $tagsIds
     *
     * @return Place
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function updateWithRelations(array $attributes, $placeId, array $specsIds, array $tagsIds): Place
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->setId($placeId)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->findOrFail($placeId);
        $model->fill($attributes);
        $model->save();

        if (array_key_exists('retail_types', $attributes) && count($attributes['retail_types']) > 0) {
            $categories = array_merge([$attributes['category']], $attributes['retail_types']);
            $model->categories()->sync($categories);
        }

        $model->tags()->sync($tagsIds);
        $model->specialities()->sync($specsIds);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }
}
