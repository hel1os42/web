<?php

namespace App\Models\NauModels;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer\RelationsTrait;
use App\Models\NauModels\Offer\ScopesTrait;
use App\Models\Traits\HasAttributes;
use App\Models\Traits\HasNau;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Offer
 * @package App\Models\NauModels
 *
 * @property string id
 * @property int account_id
 * @property string label
 * @property string description
 * @property float reward
 * @property string status
 * @property Carbon start_date
 * @property Carbon finish_date
 * @property Carbon start_time
 * @property Carbon finish_time
 * @property string country
 * @property string city
 * @property string category_id
 * @property int max_count
 * @property int max_for_user
 * @property int max_per_day
 * @property int max_for_user_per_day
 * @property int user_level_min
 * @property float latitude
 * @property float longitude
 * @property int radius
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Account account
 * @property Collection|ActivationCode[] activationCodes
 * @property Collection|Redemption[] redemptions
 */
class Offer extends NauModel
{
    use RelationsTrait, ScopesTrait, HasNau, HasAttributes;

    public function __construct(array $attributes = [])
    {
        $this->table = "offer";

        $this->primaryKey = 'id';

        $this->incrementing = false;

        $this->attributes = [
            'acc_id'               => null,
            'name'                 => null,
            'descr'                => null,
            'reward'               => 10000,
            'status'               => null,
            'dt_start'             => null,
            'dt_finish'            => null,
            'tm_start'             => null,
            'tm_finish'            => null,
            'country'              => null,
            'city'                 => null,
            'categ'                => null,
            'max_count'            => null,
            'max_for_user'         => null,
            'max_per_day'          => null,
            'max_for_user_per_day' => null,
            'min_level'            => null,
            'lat'                  => null,
            'lng'                  => null,
            'radius'               => null
        ];

        $this->fillable = [
            'account_id', 'label', 'description', 'reward',
            'start_date', 'finish_date', 'start_time', 'finish_time',
            'country', 'city', 'category_id', 'max_count', 'max_for_user',
            'max_per_day', 'max_for_user_per_day', 'user_level_min', 'latitude',
            'longitude', 'radius'
        ];

        $this->hidden = [
            'acc_id', 'name', 'descr', 'dt_start', 'dt_finish',
            'tm_start', 'tm_finish', 'categ', 'min_level', 'lat', 'lng'
        ];

        $this->appends = [
            'account_id', 'label', 'description', 'start_date',
            'finish_date', 'start_time', 'finish_time', 'category_id',
            'user_level_min', 'latitude', 'longitude', 'picture_url'
        ];

        $this->casts = [
            'id'                   => 'string',
            'account_id'           => 'integer',
            'label'                => 'string',
            'description'          => 'string',
            'status'               => 'string',
            'start_date'           => 'datetime',
            'finish_date'          => 'datetime',
            'start_time'           => 'datetime',
            'finish_time'          => 'datetime',
            'country'              => 'string',
            'city'                 => 'string',
            'category_id'          => 'string',
            'max_count'            => 'integer',
            'max_for_user'         => 'integer',
            'max_per_day'          => 'integer',
            'max_for_user_per_day' => 'integer',
            'user_level_min'       => 'integer',
            'latitude'             => 'double',
            'longitude'            => 'double',
            'radius'               => 'integer'
        ];

        $this->maps = [
            'account_id'     => 'acc_id',
            'label'          => 'name',
            'description'    => 'descr',
            'start_date'     => 'dt_start',
            'finish_date'    => 'dt_finish',
            'start_time'     => 'tm_start',
            'finish_time'    => 'tm_finish',
            'category_id'    => 'categ',
            'user_level_min' => 'min_level',
            'latitude'       => 'lat',
            'longitude'      => 'lng'
        ];

        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    public static $publicAttributes = [
        'id',
        'label',
        'description',
        'start_date',
        'finish_date',
        'start_time',
        'finish_time',
        'country',
        'city',
        'category_id',
        'latitude',
        'longitude',
        'radius'
    ];

    /**
     * @return Account
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return int */
    public function getAccountId(): int
    {
        return $this->account_id;
    }

    /** @return string */
    public function getLabel(): string
    {
        return $this->label;
    }

    /** @return string */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function getRewardAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /**
     * @param float $value
     * @return void
     */
    public function setRewardAttribute(float $value): void
    {
        $this->attributes['reward'] = $this->convertFloatToInt($value);
    }

    /** @return float */
    public function getReward(): float
    {
        return $this->reward;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /** @return Carbon */
    public function getStartDate(): Carbon
    {
        return $this->start_date;
    }

    /**
     * @return Carbon|null
     */
    public function getFinishDate(): ?Carbon
    {
        return $this->finish_date;
    }

    /**
     * @return Carbon
     */
    public function getStartTime(): Carbon
    {
        return $this->start_time;
    }

    /**
     * @return Carbon|null
     */
    public function getFinishTime(): ?Carbon
    {
        return $this->finish_time;
    }

    /** @return string */
    public function getCountry(): string
    {
        return $this->country;
    }

    /** @return string */
    public function getCity(): string
    {
        return $this->city;
    }

    /** @return string */
    public function getCategoryId(): string
    {
        return $this->category_id;
    }

    /** @return int */
    public function getMaxCount(): int
    {
        return $this->max_count;
    }

    /** @return int */
    public function getMaxForUser(): int
    {
        return $this->max_for_user;
    }

    /** @return int */
    public function getMaxPerDay(): int
    {
        return $this->max_per_day;
    }

    /** @return int */
    public function getMaxForUserPerDay(): int
    {
        return $this->max_for_user_per_day;
    }

    /** @return int */
    public function getUserLevelMin(): int
    {
        return $this->user_level_min;
    }

    /** @return float */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /** @return float */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /** @return int */
    public function getRadius(): int
    {
        return $this->radius;
    }

    /**
     * @return User|null
     */
    public function getOwner()
    {
        $account = $this->account;

        return $account === null ? null : $account->owner;
    }

    /**
     * @return null|string
     */
    public function getPictureUrlAttribute():? string
    {
        return $this->hasId() ? route('offer.picture.show', ['offerId' => $this->getId()]) : null;
    }

    /**
     * @param string $value
     */
    public function setDtStartAttribute(string $value)
    {
        $this->dt_start = Carbon::parse($value);
    }

    /**
     * @param string $value
     */
    public function setDtFinishAttribute(string $value)
    {
        $this->dt_finish = Carbon::parse($value);
    }


    /**
     * @param string $value
     */
    public function setTmStartAttribute(string $value)
    {
        $this->tm_start = Carbon::parse($value)->year(1970)->month(01)->day(01);
    }

    /**
     * @param string $value
     */
    public function setTmFinishAttribute(string $value)
    {
        $this->tm_finish = Carbon::parse($value)->year(1970)->month(01)->day(01);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user): bool
    {
        return $user->equals($this->getOwner());
    }

    /**
     * @param string $code
     *
     * @return Redemption
     * @throws BadActivationCodeException|CannotRedeemException
     */
    public function redeem(string $code)
    {
        $activationCode = $this->activationCodes()->byCode($code)->first();

        if (null === $activationCode) {
            throw new BadActivationCodeException($this, $code);
        }

        $redemption = $this->redemptions()->create(['user_id' => $activationCode->getUserId()]);
        if (null === $redemption->getId()) {
            throw new CannotRedeemException($this, $activationCode->getCode());
        }

        $activationCode->activated($redemption);

        return $redemption;
    }

    /**
     * @return bool
     */
    public function hasId(): bool
    {
        return !is_null($this->id);
    }
}
