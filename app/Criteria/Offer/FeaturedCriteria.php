<?php

namespace App\Criteria\Offer;

use App\Models\OfferData;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FeaturedOfferCriteriaCriteria
 *
 * @package namespace App\Criteria;
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class FeaturedCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $featuredOfferIds = OfferData::query()
            ->featured()
            ->pluck('id');

        return $model->whereIn('id', $featuredOfferIds);
    }
}
