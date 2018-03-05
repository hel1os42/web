<?php

namespace App\Repositories;

use App\Models\Place;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PlaceRepository
 * @package namespace App\Repositories;
 *
 * @method Place first($columns = ['*'])
 * @method Place find($id, $columns = ['*'])
 * @method Place update(array $attributes, $id)
 */
interface PlaceRepository extends RepositoryInterface
{
    public function model(): string;

    public function createForUserOrFail(array $placeData, User $user, array $specsIds, array $tagsIds): Place;

    /**
     * @param User $user
     *
     * @return Place
     * @throws ModelNotFoundException
     */
    public function findByUser(User $user): Place;

    /**
     * @param $categoryIds
     * @param $latitude
     * @param $longitude
     * @param $radius
     *
     * @return Builder
     */
    public function getActiveByCategoriesAndPosition(
        array $categoryIds,
        float $latitude,
        float $longitude,
        int $radius
    ): Builder;

    public function countByUser(User $user): int;

    public function existsByUser(User $user): bool;

    public function parserResult($places);

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
    public function updateWithRelations(array $attributes, $placeId, array $specsIds, array $tagsIds): Place;
}
