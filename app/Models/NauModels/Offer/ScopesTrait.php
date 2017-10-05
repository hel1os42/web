<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 22.09.17
 * Time: 16:02
 */

namespace App\Models\NauModels\Offer;

use App\Models\NauModels\Offer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Trait ScopesTrait
 * @package App\Models\NauModels\Offer
 *
 * @method static static|Builder accountOffers(int $accountId) : Offer
 * @method static static|Builder filterByPosition(string $latitude, string $longitude, int $radius) : Offer
 * @method static static|Builder filterByCategory(string $categoryId = null)
 * @method static static|Builder filterByCategories(array $categoryIds)
 * @method static static|Builder byOwner(User $user)
 * @method static static|Builder active()
 */
trait ScopesTrait
{
    /**
     * @param Builder $builder
     * @param int     $accountId
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeAccountOffers(Builder $builder, int $accountId): Builder
    {
        return $builder->where('account_id', $accountId);
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
            return $builder;
        }

        return $builder->whereRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((lat - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(lat * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((lng - %2$s) * 
        PI()/180 / 2), 2)))) < (radius + %3$d)',
            DB::connection()->getPdo()->quote($lat),
            DB::connection()->getPdo()->quote($lng),
            $radius));
    }

    /**
     * @param Builder     $builder
     * @param string|null $categoryId
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByCategory(Builder $builder, string $categoryId = null): Builder
    {
        return !Uuid::isValid($categoryId) ? $builder : $builder->where('category_id', $categoryId);
    }

    /**
     * @param Builder $builder
     * @param array   $categoryIds
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByCategories(Builder $builder, array $categoryIds): Builder
    {
        return count($categoryIds) >= 1 ? $builder->whereIn('category_id', $categoryIds) : $builder;
    }

    public function scopeByOwner(Builder $builder, User $owner): Builder
    {
        return $builder->whereIn('account_id', $owner->accounts->pluck('id'));
    }

    public function scopeActive(Builder $builder): Builder
    {
        $now = Carbon::now()->format(Carbon::ISO8601);

        return $builder->where('status', Offer::STATUS_ACTIVE)
            ->where('start_date', '<=', $now)
            ->where(function(Builder $builder) use ($now) {
                $builder
                    ->whereNull('finish_date')
                    ->orWhere('finish_date', '>', $now);
            });
    }
}
