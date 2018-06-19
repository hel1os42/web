<?php

namespace App\Repositories;

use App\Models\NauModels\Redemption;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RedemptionRepository
 * NS: App\Repositories
 *
 * @method Redemption find($id, $columns = ['*'])
 */
interface RedemptionRepository extends RepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string;

    public function count(): int;

    /**
     * @param array $offersId
     *
     * @return Collection
     */
    public function findForOffers(array $offersId): Collection;
}
