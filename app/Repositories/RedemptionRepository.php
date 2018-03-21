<?php

namespace App\Repositories;

use App\Models\NauModels\Redemption;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\User;

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
}
