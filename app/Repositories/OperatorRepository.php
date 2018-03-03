<?php

namespace App\Repositories;

use App\Http\Exceptions\InternalServerErrorException;
use Illuminate\Foundation\Testing\HttpException;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\Place;
use App\Models\Operator;
use Illuminate\Support\Collection;

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

    /**
     * @param Place $place
     *
     * @return Collection
     */
    public function findByPlace(Place $place): Collection;

    /**
     * @param Place $place
     * @param string $login
     *
     * @return Operator|null
     */
    public function findByPlaceAndLogin(Place $place, string $login): ?Operator;
}
