<?php

namespace App\Repositories;

use App\Models\Place;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface OfferLinkRepository
 * @package namespace App\Repositories;
 *
 */
interface OfferLinkRepository extends RepositoryInterface
{
    /**
     * @param Place $place
     *
     * @return OfferLinkRepository
     */
    public function scopePlace(Place $place): OfferLinkRepository;
}
