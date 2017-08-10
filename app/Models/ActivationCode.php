<?php

namespace App\Models;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class ActivationCode
 * @package App
 *
 * @property integer id
 * @property string code
 * @property string user_id
 * @property string offer_id
 * @property string redemption_id
 * @method checkOffer(string $offerId)
 */
class ActivationCode extends Model
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->table = 'activation_codes';

        $this->timestamps = false;

        $this->appends = ['code'];
    }

    protected $fillable = [
        'offer_id',
        'user_id'
    ];

    /** @return string */
    public function getCode(): string
    {
        return $this->code;
    }

    /** @return string */
    public function getCodeAttribute(): string
    {
        return Hashids::connection('activation_code')->encode($this->id);
    }

    /**
     * @param string $uuid
     * @return ActivationCode
     */
    public function setRedemptionId(string $uuid): ActivationCode
    {
        $this->redemption_id = $uuid;
        return $this;
    }

    /** @return BelongsTo */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** @return BelongsTo */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    /** @return BelongsTo */
    public function redemption(): BelongsTo
    {
        return $this->belongsTo(Redemption::class, 'redemption_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ActivationCode $model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * @param string $code
     * @return int
     */
    public function getIdByCode(string $code): int
    {
        return Hashids::connection('activation_code')->decode($code)[0];
    }
}
