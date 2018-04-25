<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 04.04.2018
 * Time: 12:11
 */

namespace App\Models\Place;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait ScopesTrait
{
    /**
     * @param Builder $builder
     * @param string  $alias
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByAlias($builder, string $alias): Builder
    {
        return $builder->where('alias', $alias);
    }


    /**
     * @param Builder $builder
     * @param User    $user
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByUser($builder, User $user): Builder
    {
        return $builder->where('user_id', $user->getId());
    }

    /**
     * @param Builder     $builder
     * @param string|null $lat
     * @param string|null $lng
     * @param int|null    $radius
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByPosition(
        Builder $builder,
        string $lat = null,
        string $lng = null,
        int $radius = null
    ): Builder {
        if (empty($lat) || empty($lng) || $radius < 1) {
            return $builder->whereNull('latitude')->whereNull('longitude')->whereNull('radius');
        }

        return $builder->whereRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((latitude - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(latitude * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((longitude - %2$s) * 
        PI()/180 / 2), 2)))) < (radius + %3$d)',
            $this->getConnection()->getPdo()->quote($lat),
            $this->getConnection()->getPdo()->quote($lng),
            $radius));
    }

    /**
     * @param Builder     $builder
     * @param string|null $lat
     * @param string|null $lng
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeOrderByPosition(Builder $builder, string $lat = null, string $lng = null): Builder
    {
        if (isset($lat, $lng)) {
            return $builder->orderByRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((latitude - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(latitude * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((longitude - %2$s) * 
        PI()/180 / 2), 2))))',
                $this->getConnection()->getPdo()->quote($lat),
                $this->getConnection()->getPdo()->quote($lng)));
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array   $categoryIds
     *
     * @return Builder
     */
    public function scopeFilterByCategories(Builder $builder, array $categoryIds): Builder
    {
        return $builder->whereHas('categories', function (Builder $builder) use ($categoryIds) {
            $builder->whereIn('id', $categoryIds)->whereNull('parent_id');
        });
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByActiveOffersAvailability(Builder $builder): Builder
    {
        return $builder->where('has_active_offers', '=', true);
    }
}
