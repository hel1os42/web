<?php

namespace App\Repositories\Implementation;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Category $model
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Category::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return Builder
     */
    public function getWithNoParent(): Builder
    {
        $this->applyCriteria();

        return $this->model->withNoParent()->ordered();
    }

    /**
     * @param string $parentId
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function getSubcategory(string $parentId): Builder
    {
        $this->applyCriteria();

        return $this->model->where('parent_id', $parentId);
    }

    public function ordered(): Builder
    {
        return $this->model->ordered();
    }
}
