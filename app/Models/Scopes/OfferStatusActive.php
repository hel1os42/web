<?php

namespace App\Models\Scopes;

use App\Models\NauModels\Offer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OfferStatusActive implements Scope
{
    /**
     * @param Builder $builder
     * @param Model   $model
     *
     * @throws \InvalidArgumentException
     * @SuppressWarnings("unused")
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', Offer::STATUS_ACTIVE);
    }
}
