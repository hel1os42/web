<?php

namespace App\Models\Traits;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\Account;
use App\Models\NauModels\User as CoreUser;
use App\Models\User;
use App\Models\UserOfferPivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

/**
 * Trait UserRelationsTrait
 *
 * @package App\Models\Traits
 */
trait UserRelationsTrait
{
    /**
     * Get the referrer record associated with the user.
     *
     * @return Relations\BelongsTo
     */
    public function referrer(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user referrals.
     *
     * @return Relations\HasMany
     */
    public function referrals(): Relations\HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    /**
     * Get the user accounts relation
     *
     * @return Relations\HasMany
     */
    public function accounts(): Relations\HasMany
    {
        return $this->hasMany(Account::class, 'owner_id', 'id');
    }

    /**
     * @return Relations\HasMany
     */
    public function activationCodes(): Relations\HasMany
    {
        return $this->hasMany(ActivationCode::class);
    }

    /**
     * @return Relations\BelongsToMany
     */
    public function offers(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Offer::class, (new Redemption)->getTable())
                    ->withPivot([
                        'created_at',
                        'points',
                    ]);
    }

    /**
     * @return Relations\HasOne
     */
    public function coreUser(): Relations\HasOne
    {
        return $this->hasOne(CoreUser::class, 'id', 'id');
    }
}
