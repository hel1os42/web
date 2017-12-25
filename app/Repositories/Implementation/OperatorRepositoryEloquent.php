<?php

namespace App\Repositories\Implementation;

use App\Http\Exceptions\InternalServerErrorException;
use App\Repositories\OperatorRepository;
use Illuminate\Foundation\Testing\HttpException;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Operator;
use App\Models\Place;
use Prettus\Validator\Contracts\ValidatorInterface;


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

    /**
     * @param array $attributes
     * @param Place $place
     *
     * @return Operator
     * @return InternalServerErrorException
     * @return HttpException
     */
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
            logger()->error('cannot save operator', $attributes);
            throw new InternalServerErrorException('Cannot save operator');
        }

        return $this->parserResult($model);
    }

    /**
     * @param string $operatorUuid
     * @param string $placeUuid
     *
     * @return Operator|null
     */
    public function findByIdAndPlaceId(string $operatorUuid, string $placeUuid): ?Operator
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model->where(['place_uuid' => $placeUuid])->find($operatorUuid);

        $this->resetModel();

        return $this->parserResult($model);
    }
}
