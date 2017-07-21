<?php

namespace App\Models;

use App\Models\Traits\HasNau;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Redemption
 * @package App
 *
 * @property string id
 * @property string offer_id
 * @property string user_id
 * @property int points
 * @property string rewarded_id
 * @property int amount
 * @property int fee
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Offer offer
 * @property User rewarded
 */
class Redemption extends Model
{
    use ReadOnlyTrait;
    use HasNau;

    /** @var string */
    protected $table = "redemption";

    /** @var array */
    protected $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array */
    protected $casts = [
        'id'            => 'string',
        'offer_id'      => 'string',
        'user_id'       => 'string',
        'points'        => 'integer',
        'rewarded_id'   => 'string',
        'amount'        => 'integer',
        'fee'           => 'integer',
    ];

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getOfferId(): string
    {
        return $this->offer_id;
    }

    /** @return string */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /** @return int */
    public function getPoints(): int
    {
        return $this->points;
    }

    /** @return string */
    public function getRewardedId(): string
    {
        return $this->rewarded_id;
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
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param int $value
     * @return float
     */
    public function getFeeAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /** @return float */
    public function getFee(): float
    {
        return $this->fee;
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

    /** @return BelongsTo */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    /** @return BelongsTo */
    public function rewarded(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rewarded_id', 'id');
    }
}