<?php

namespace App\Criteria\Offer;

use Illuminate\Support\Facades\DB;
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
        $featuredOfferIds = DB::table('offers_data')
            ->where('is_featured', true)
            ->select('id')
            ->get()
            ->pluck('id');

        return $model->whereIn('id', $featuredOfferIds);
    }
}
