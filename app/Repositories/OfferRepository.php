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
     * @param null $latitude
     * @param null $longitude
     *
     * @return Builder
     */
    public function groupAndOrderByPosition($latitude = null, $longitude = null): Builder;

    /**
     * @param string $with
     *
     * @return array
     */
    public function getPlaces(string $with): array;

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
