<?php

namespace App\Repositories\Implementation;

use App\Models\Tag;
use App\Repositories\TagRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TagRepositoryEloquent
 * @package App\Repositories\Implementation
 */
class TagRepositoryEloquent extends BaseRepository implements TagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Tag::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param string $categoryId
     * @param array  $slugs
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function findIdsByCategoryAndSlugs(string $categoryId, array $slugs): array
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->whereIn('slug', $slugs)
            ->pluck('id')
            ->toArray();
    }
}
