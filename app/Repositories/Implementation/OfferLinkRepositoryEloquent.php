<?php

namespace App\Repositories\Implementation;

use App\Models\OfferLink;
use App\Models\Place;
use App\Repositories\OfferLinkRepository;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class OfferRepositoryEloquent
 * @package namespace App\Repositories;
 *
 * @property OfferLink $model
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferLinkRepositoryEloquent extends BaseRepository implements OfferLinkRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OfferLink::class;
    }

    /**
     * @param Place $place
     *
     * @return OfferLinkRepository
     */
    public function scopePlace(Place $place): OfferLinkRepository
    {
        return $this->scopeQuery(
            function ($builder) use ($place) {
                return $builder->where('place_id', $place->getKey());
            }
        );
    }
}
