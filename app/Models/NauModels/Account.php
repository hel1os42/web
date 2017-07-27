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

    /** @var string */
    protected $table = 'account';

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array */
    protected $casts = [
        'id'         => 'integer',
        'owner_id'   => 'string',
        'addr'       => 'string',
        'amount'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];

    /** @var array */
    protected $maps = [
        'balance' => 'amount',
        'address' => 'addr',
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
    public function getBalanceAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /** @return float */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /** @return Carbon */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /** @return Carbon */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }
}
