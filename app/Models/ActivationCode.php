<?php

namespace App\Models;

use App\Exceptions\BadActivationCodeException;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Vinkla\Hashids\Facades\Hashids;

/**
 * Class ActivationCode
 * @package App\Models
 *
 * @property integer id
 * @property string code
 * @property string user_id
 * @property string offer_id
 * @property string redemption_id
 * @property Carbon created_at
 * @property Offer offer
 * @property User user
 * @property Redemption redemption
 * @method ActivationCode byCode(string $code)
 */
class ActivationCode extends Model
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = [
            'offer_id',
            'user_id'
        ];

        $this->connection = config('database.default');

        $this->table = 'activation_codes';

        $this->appends = ['code'];

        parent::__construct($attributes);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
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
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /** @return BelongsTo */
    public function redemption(): BelongsTo
    {
        return $this->belongsTo(Redemption::class);
    }

    /**
     * @param string $code
     * @return int
     * @throws BadActivationCodeException
     */
    public function getIdByCode(string $code): ?int
    {
        $activationId = Hashids::connection('activation_code')->decode($code);
        return isset($activationId[0]) ? $activationId[0] : null;

    }

    /**
     * @param Builder $builder
     * @param string $code
     * @return Builder
     */
    public function scopeByCode(Builder $builder, string $code): Builder
    {
        return $builder->where('id', $this->getIdByCode($code));
    }

    /**
     * @param Redemption $redemption
     */
    public function activated(Redemption $redemption)
    {
        $this->redemption()->associate($redemption)->update();
    }
}
