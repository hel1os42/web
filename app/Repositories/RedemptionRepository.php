<?php

namespace App\Repositories;

use App\Models\NauModels\Redemption;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RedemptionRepository
 * NS: App\Repositories
 *
 * @method Redemption find($id, $columns = ['*'])
 */
interface RedemptionRepository extends RepositoryInterface
{
    public function model(): string;

    public function count(): int;
}
