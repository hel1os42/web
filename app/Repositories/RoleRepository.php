<?php

namespace App\Repositories;

use App\Models\Role;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RoleRepository
 * @package namespace App\Repositories;
 *
 * @method Role find($id, $columns = ['*'])
 */
interface RoleRepository extends RepositoryInterface
{
    public function model(): string;
}
