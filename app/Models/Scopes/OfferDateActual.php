<?php

namespace App\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OfferDateActual implements Scope
{
    /**
     * @param Builder $builder
     *
     * @throws \InvalidArgumentException
     * @SuppressWarnings("unused")
     */
    public function apply(Builder $builder, Model $model)
    {
        $now = Carbon::now()->format(Carbon::ISO8601);

        $builder
            ->where('start_date', '<=', $now)
            ->where(function(Builder $builder) use ($now) {
                $builder
                    ->whereNull('finish_date')
                    ->orWhere('finish_date', '>', $now);
            });
    }
}
