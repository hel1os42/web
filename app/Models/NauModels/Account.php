<?php

namespace App\Models\NauModels;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Account
 * @package App
 *
 * @property int id
 * @property string owner_id
 * @property string address
 * @property int balance
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection|Offer[] offers
 * @property User owner
 * @property bool isEnoughBalanceFor
 * @property Builder scopeWhereOwnerId
 */
class Account extends NauModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

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
    }

    /** @var array */
    protected $maps = [
        'balance' => 'amount',
        'address' => 'addr'
    ];

    /** @return BelongsTo */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
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
    public function getaddress(): string
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
     * @param $ownerId
     * @return Builder
     */
    public function scopeWhereOwnerId(Builder $builder, string $ownerId): Builder
    {
        return $builder->where('owner_id', $ownerId);
    }

}
