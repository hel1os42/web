<?php

namespace App\Models\NauModels;

use App\Models\Traits\HasNau;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Account
 * @package App\Models\NauModels
 *
 * @property int id
 * @property string owner_id
 * @property string address
 * @property int balance
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection|Offer[] offers
 * @property User owner
 *
 * @method static static|Builder byAddress(string $address)
 * @method static static|Builder byOwner(User $owner)
 */
class Account extends AbstractNauModel
{
    use HasNau;

    public function __construct(array $attributes = [])
    {
        $this->table = 'account';

        $this->primaryKey = 'id';

        $this->casts = [
            'id'       => 'integer',
            'owner_id' => 'string',
            'addr'     => 'string',
            'amount'   => 'integer'
        ];

        $this->appends = [
            'balance',
            'address'
        ];

        $this->hidden = [
            'amount',
            'addr'
        ];

        $this->maps = [
            'balance' => 'amount',
            'address' => 'addr',
        ];

        parent::__construct($attributes);
    }

    /** @return BelongsTo */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /** @return User */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /** @return HasMany */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'acc_id', 'id');
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getOwnerId(): string
    {
        return $this->owner_id;
    }

    /** @return string */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param int $value
     * @return float
     */
    public function getAmountAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /** @return float */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param Builder $builder
     * @param string  $address
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByAddress(Builder $builder, string $address): Builder
    {
        return $builder->where('address', $address);
    }

    /**
     * @param Builder $builder
     * @param User    $owner
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByOwner(Builder $builder, User $owner): Builder
    {
        return $builder->where('owner_id', $owner->id);
    }
}
