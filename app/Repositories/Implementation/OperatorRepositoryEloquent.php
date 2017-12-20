<?php

namespace App\Repositories\Implementation;

use App\Repositories\OperatorRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Operator;
use App\Models\Place;

/**
 * Class OperatorRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OperatorRepositoryEloquent extends BaseRepository implements OperatorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Operator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function createForPlaceOrFail(array $attributes, Place $place): Operator
    {
        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $model = $this->model->newInstance($attributes);
        $model->place()->associate($place);
        $model->save();

        $this->resetModel();

        if (!$model->exists) {
            logger()->error('cannot save opertaor', $attributes);
            throw new InternalServerErrorException('Cannot save operator');
        }

        //event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    public function findByIdAndPlaceId(string $operatorUuid, string $PlaceUuid): ?Operator
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->where(['place_uuid' => $PlaceUuid])->find($operatorUuid);

        $this->resetModel();

        return $this->parserResult($model);
    }
}
