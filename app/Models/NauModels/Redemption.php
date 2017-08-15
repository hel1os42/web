<?php

namespace App\Models\NauModels;

use App\Models\User;
use Carbon\Carbon;
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
 * @property User rewardedUser
 * @property User user
 */
class Redemption extends NauModel
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = "redemption";

        $this->primaryKey = 'id';

        $this->fillable = [
            'offer_id',
            'user_id',
            'points',
            'rewarded_id',
            'amount',
            'fee',
        ];

        $this->casts = [
            'id'          => 'string',
            'offer_id'    => 'string',
            'user_id'     => 'string',
            'points'      => 'integer',
            'rewarded_id' => 'string',
            'amount'      => 'integer',
            'fee'         => 'integer'
        ];
    }

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

    /** @return BelongsTo */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    /** @return BelongsTo */
    public function rewardedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rewarded_id', 'id');
    }

    /** @return BelongsTo */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
