<?php

namespace App\Models;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * Class ActivationCode
 * @package App\Models
 *
 * @property integer    id
 * @property string     code
 * @property string     user_id
 * @property string     offer_id
 * @property string     redemption_id
 * @property Carbon     created_at
 * @property Offer      offer
 * @property User       user
 * @property Redemption redemption
 *
 * @method static static|ActivationCode[]|Collection|Builder byCode(string $code)
 * @method static static|ActivationCode[]|Collection|Builder byOwner(User $owner)
 * @method static static|ActivationCode[]|Collection|Builder byOffer(Offer $offer)
 */
class ActivationCode extends Model
{
    const LIFETIME_ACTIVATION_CODE = 15;

    /**
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
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
        return app('hashids')->connection('activation_code')->encode($this->id);
    }

    /**
     * @param string $uuid
     *
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
     *
     * @return int
     * @throws BadActivationCodeException
     */
    public function getIdByCode(string $code): ?int
    {
        $activationId = app('hashids')->connection('activation_code')->decode($code);

        return isset($activationId[0]) ? $activationId[0] : null;

    }

    /**
     * @param Builder $builder
     * @param string  $code
     *
     * @return Builder
     * @throws BadActivationCodeException
     * @throws \InvalidArgumentException
     */
    public function scopeByCode(Builder $builder, string $code): Builder
    {
        return $builder->where('id', $this->getIdByCode($code));
    }

    /**
     * @param Builder $builder
     * @param User    $owner
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByOwner(Builder $builder, User $owner): Builder
    {
        return $builder->where('user_id', $owner->getId());
    }

    /**
     * @param Builder $builder
     * @param Offer   $offer
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByOffer(Builder $builder, Offer $offer): Builder
    {
        return $builder->where('offer_id', $offer->getId());
    }

    /**
     * @param Redemption $redemption
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function activated(Redemption $redemption)
    {
        $this->redemption()->associate($redemption)->update();
    }

    public function getOfferAttribute()
    {
        return $this->getRelationValue('offer');
    }

    /**
     * @param Void
     *
     * @return bool
     */
    public function validity(): bool
    {
        return $this->created_at > Carbon::now()->subMinute(ActivationCode::LIFETIME_ACTIVATION_CODE);
    }
}
