<?php

namespace App\Repositories\Implementation;

use App\Models\Place;
use App\Models\Testimonial;
use App\Models\User;
use App\Repositories\TestimonialRepository;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Validator\Contracts\ValidatorInterface;

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
    public function getByPlace(Place $place): Builder
    {
        $this->skipCriteria();

        $result = $this->model
            ->byPlace($place)
            ->whereNotNull('text')
            ->orderBy('created_at', 'desc');
        $this->resetModel();

        return $result;
    }

    /**
     * @param Place $place
     *
     * @return int
     */
    public function countStarsForPlace(Place $place): int
    {
        $stars      = $place->getStars();
        $starsArray = $this->model
            ->byPlace($place)
            ->get()
            ->toArray();
        if (count($starsArray)) {
            $starsArray = array_column($starsArray, 'stars');
            $stars      = round(array_sum($starsArray) / count($starsArray));
        }
 
        return $stars;
    }

    /**
     * @param array $attributes
     * @param Place $place
     * @param User  $user
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createOrUpdateIfExist(array $attributes, Place $place, User $user)
    {
        $model = $this->model->byPlaceAndUser($place, $user)->first();

        if ($model !== null) {
            $attributes['text'] = $model->getText();

            return $this->update($attributes, $model->getId());
        }

        return $this->create($attributes);
    }
}
