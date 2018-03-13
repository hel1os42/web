<?php

namespace App\Repositories\Implementation;

use App\Models\Place;
use App\Models\Testimonial;
use App\Repositories\TestimonialRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TestimonialRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property Testimonial $model
 */
class TestimonialRepositoryEloquent extends BaseRepository implements TestimonialRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Testimonial::class;
    }

    /**
     * Boot up the repository, pushing criteria
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param Place $place
     *
     * @return Builder
     * @throws \InvalidArgumentException
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function getApprovedByPlace(Place $place): Builder
    {
        $this->skipCriteria();

        $result = $this->model
            ->byPlace($place)
            ->where('status', Testimonial::STATUS_APPROVED)
            ->orderBy('created_at', 'desc');
        $this->resetModel();

        return $result;
    }
}
