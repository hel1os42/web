<?php

namespace App\Models\NauModels;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

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
    public function getBalanceAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /** @return float */
    public function getBalance(): float
    {
        return $this->balance;
    }
}
