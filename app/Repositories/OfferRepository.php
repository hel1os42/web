<?php

namespace App\Repositories;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
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

    /**
     * @param array $attributes
     * @param Account $account
     *
     * @return Offer
     */
    public function createForAccountOrFail(array $attributes, Account $account): Offer;

    /**
     * @param array      $categoryIds
     * @param float|null $latitude
     * @param float|null $longitude
     * @param int|null   $radius
     *
     * @return Builder
     */
    public function getActiveByCategoriesAndPosition(
        array $categoryIds,
        ?float $latitude,
        ?float $longitude,
        ?int $radius
    ): Builder;

    /**
     * @param Account $account
     *
     * @return OfferRepository
     */
    public function scopeAccount(Account $account): OfferRepository;

    /**
     * @param array  $attributes
     * @param string $offerId
     *
     * @return Offer
     */
    public function update(array $attributes, $offerId): Offer;

    /**
     * @param string $offerId
     * @param array  $columns
     *
     * @return Offer|null
     */
    public function findWithoutGlobalScopes(string $offerId, array $columns = ['*']): Offer;
}
