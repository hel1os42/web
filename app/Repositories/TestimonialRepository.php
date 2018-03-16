<?php

namespace App\Repositories;

use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\Testimonial;

/**
 * Interface TestimonialRepository
 * @package namespace App\Repositories;
 *
 * @method Testimonial find($id, $columns = ['*'])
 * @method Testimonial all($columns = ['*'])
 */
interface TestimonialRepository extends RepositoryInterface
{
    public function model(): string;

    public function createOrUpdateIfExist(array $attributes, Place $place, User $user);

    public function getByPlace(Place $place): Builder;

    public function countStarsForPlace(Place $place): int;
}
