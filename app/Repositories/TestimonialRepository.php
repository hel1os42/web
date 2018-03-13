<?php

namespace App\Repositories;

use App\Models\Place;
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

    public function getApprovedByPlace(Place $places): Builder;
}
