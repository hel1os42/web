<?php

namespace App\Repositories\Implementation;

use App\Models\Speciality;
use App\Repositories\SpecialityRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SpecialityRepositoryEloquent
 * @package App\Repositories\Implementation
 */
class SpecialityRepositoryEloquent extends BaseRepository implements SpecialityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Speciality::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param string $retailTypeId
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function findByRetailType(string $retailTypeId): Builder
    {
        $this->applyCriteria();

        return $this->model->where('retail_type_id', $retailTypeId);
    }

    /**
     * @param string $retailTypeId
     * @param array  $slugs
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function findIdsByRetailTypeAndSlugs(string $retailTypeId, array $slugs): array
    {
        return $this->model
            ->where('retail_type_id', $retailTypeId)
            ->whereIn('slug', $slugs)
            ->pluck('id')
            ->toArray();
    }
}
