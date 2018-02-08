<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CategoryRepository
 * @package namespace App\Repositories;
 *
 * @method Category find($id, $columns = ['*'])
 */
interface CategoryRepository extends RepositoryInterface
{
    public function model(): string;

    public function getWithNoParent(): Builder;

    public function getSubcategory(string $parentId): Builder;
}
