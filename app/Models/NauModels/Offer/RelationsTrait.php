<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 11.09.17
 * Time: 19:06
 */

namespace App\Models\NauModels\Offer;

use App\Models\ActivationCode;
use App\Models\UserOfferPivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \App\Models\User;

/**
 * Trait RelationsTrait
 * @package App\Models\NauModels\Offer
 */
trait RelationsTrait
{
    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return HasMany
     */
    public function activationCodes(): HasMany
    {
        return $this->hasMany(ActivationCode::class);
    }

    /**
     * @return HasMany
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class);
    }

    /**
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     * @param null   $using
     *
     * @return UserOfferPivot|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if ($parent instanceof User) {
            return new UserOfferPivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }
}
