<?php

namespace App\Models;

use App\Models\Traits\HasNau;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
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
 * @property Collection|Account[] offer
 * @property User owner
 */
class Account extends Model
{
    use ReadOnlyTrait;
    use HasNau;

    /** @var string */
    protected $table = "account";

    /** @var array */
    protected $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array */
    protected $maps = [
        'amount' => 'balance',
        'addr'   => 'address',
    ];

    /** @var array */
    protected $casts = [
        'id'       => 'integer',
        'owner_id' => 'string',
        'address'  => 'string',
        'balance'  => 'integer',
    ];

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

    /** @return BelongsTo */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
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

    /** @return HasMany */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'acc_id', 'id');
    }
}