<?php

namespace App\Repositories;

use App\Http\Exceptions\InternalServerErrorException;
use Illuminate\Foundation\Testing\HttpException;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\Place;
use App\Models\Operator;

/**
 * Interface OperatorRepository
 * @package namespace App\Repositories;
 */
interface OperatorRepository extends RepositoryInterface
{
    public function model(): string;

    /**
     * @param array $attributes
     * @param Place $place
     *
     * @return Operator
     * @return InternalServerErrorException
     * @return HttpException
     */
    public function createForPlaceOrFail(array $attributes, Place $place): Operator;

    /**
     * @param string $operatorUuid
     * @param string $placeUuid
     *
     * @return Operator|null
     */
    public function findByIdAndPlaceId(string $operatorUuid, string $placeUuid): ?Operator;
}
