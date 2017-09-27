<?php

namespace App\Repositories;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OfferRepository
 * @package namespace App\Repositories;
 *
 * @method Offer find($id, $columns = ['*'])
 */
interface OfferRepository extends RepositoryInterface
{
    public function model(): string;

    public function createForAccountOrFail(array $attributes, Account $account): Offer;

    public function findByIdAndOwner(string $identity, User $user): ?Offer;

    /**
     * @param $categoryIds
     * @param $latitude
     * @param $longitude
     * @param $radius
     *
     * @return Builder
     */
    public function getByCategoriesAndPosition(
        array $categoryIds,
        float $latitude,
        float $longitude,
        int $radius
    ): Builder;

    public function findActiveByIdOrFail(string $identity): Offer;
}
