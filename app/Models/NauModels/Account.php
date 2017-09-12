<?php

namespace App\Models\NauModels;

use App\Models\Traits\HasNau;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

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
 * @method static Account whereAddress
 */
class Account extends NauModel
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
     * @param float $amount
     * @return bool
     */
    public function isEnoughBalanceFor(float $amount): bool
    {
        return $this->getBalance() >= $amount;
    }

    /**
     * @param Builder $builder
     * @param string  $address
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeWhereAddress(Builder $builder, string $address): Builder
    {
        return $builder->where('address', $address);
    }
}
