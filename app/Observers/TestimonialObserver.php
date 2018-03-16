<?php

namespace app\Observers;

use App\Models\Testimonial;
use App\Repositories\TestimonialRepository;

/**
 * Class TestimonialObserver
 * @package app\Observers
 */
class TestimonialObserver
{
    /**
     * @param Testimonial $testimonial
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function saved(Testimonial $testimonial)
    {
        if ($testimonial->getStatus() === Testimonial::STATUS_APPROVED) {
            $place = $testimonial->place;
            $stars = app(TestimonialRepository::class)->countStarsForPlace($place);
            $place->setStars($stars)->update();
        }
    }
}
