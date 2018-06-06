<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class OfferData
 * @package App\Models
 *
 * @property string                      id
 * @property boolean                     delivery
 * @property null|string                 type
 * @property null|string                 gift_bonus_descr
 * @property null|float                  discount_percent
 * @property null|float                  discount_start_price
 * @property null|float                  discount_finish_price
 * @property null|string                 currency
 * @property null|string                 owner_id
 * @property int                         timeframes_offset
 * @property \App\Models\NauModels\Offer $offer
 */
class OfferData extends Model
{
    use Uuids;

    const OFFER_TYPE_DISCOUNT = 'discount';
    const OFFER_TYPE_GIFT     = 'gift';
    const OFFER_TYPE_BONUS    = 'bonus';
    const OFFER_TYPE_2NDFREE  = 'second_free';

    const OFFER_TYPES = [
        self::OFFER_TYPE_DISCOUNT,
        self::OFFER_TYPE_GIFT,
        self::OFFER_TYPE_BONUS,
        self::OFFER_TYPE_2NDFREE,
    ];

    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'offers_data';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'id'                      => 'string',
            'delivery'                => 'boolean',
            'type'                    => 'string',
            'gift_bonus_descr'        => 'string',
            'discount_percent'        => 'float',
            'discount_start_price'    => 'float',
            'currency'                => 'string',
            'owner_id'                => 'string',
            'referral_points_price'   => 'integer',
            'redemption_points_price' => 'integer',
            'is_featured'             => 'boolean',
        ];

        $this->fillable = [
            'delivery',
            'type',
            'gift_bonus_descr',
            'discount_percent',
            'discount_start_price',
            'currency',
            'owner_id',
            'timeframes_offset',
            'referral_points_price',
            'redemption_points_price',
            'is_featured',
        ];

        $this->attributes = [
            'delivery'                => false,
            'type'                    => null,
            'gift_bonus_descr'        => null,
            'discount_percent'        => null,
            'discount_start_price'    => null,
            'currency'                => null,
            'referral_points_price'   => 0,
            'redemption_points_price' => 0,
            'is_featured'             => false,
        ];

        $this->appends = [
            'discount_finish_price'
        ];

        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /**
     * @param float $value
     *
     * @return void
     */
    public function setDiscountPercentAttribute(?float $value): void
    {
        $this->attributes['discount_percent'] = round(abs((float)$value), 2);
    }

    /**
     * @param float $value
     *
     * @return void
     */
    public function setDiscountStartPriceAttribute(?float $value): void
    {
        $this->attributes['discount_start_price'] = round(abs((float)$value), 2);
    }

    /**
     * @return float|null
     */
    public function getDiscountFinishPriceAttribute(): ?float
    {
        return ($this->discount_start_price > 0)
            ? round($this->discount_start_price * (1 - 0.01 * $this->discount_percent), 2)
            : null;
    }

    /**
     * @param null|string $ownerId
     *
     * @return OfferData
     */
    public function setOwnerId(?string $ownerId): OfferData
    {
        $this->owner_id = $ownerId;

        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function offer(): BelongsTo
    {
        // here we store offer id in offerData model
        return $this->belongsTo(OfferData::class, 'id', 'id')
                    ->withDefault(function ($model) {
                        $model->id = $this->getId();
                    });
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }
}
