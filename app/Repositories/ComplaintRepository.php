<?php

namespace App\Repositories;

use App\Models\Complaint;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ComplaintRepository
 * @package namespace App\Repositories;
 *
 * @method Complaint find($id, $columns = ['*'])
 * @method Complaint all($columns = ['*'])
 * @method Complaint create($attributes)
 */
interface ComplaintRepository extends RepositoryInterface
{
    public function model(): string;
}
