<?php

namespace App\Models\NauModels;

use App\Models\User;
use Carbon\Carbon;
use Sofa\Eloquence\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Class Offer
 * @package App
 *
 * @property string id
 * @property int account_id
 * @property string label
 * @property string description
 * @property Float reward
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
 * @property double latitude
 * @property double longitude
 * @property int radius
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Account account
 * @method static filterByPosition(string $latitude, string $longitude, int $radius) : Offer
 * @method static accountOffers(int $accountId) : Offer
 *
 */
class Offer extends NauModel
{

    /** @var string */
    protected $table = "offer";

    /** @var string */
    protected $primaryKey = 'id';

    /** @var array */
    protected $casts = [
        'id'                   => 'string',
        'account_id'           => 'integer',
        'label'                => 'string',
        'description'          => 'string',
        'status'               => 'string',
        'start_date'           => 'date',
        'finish_date'          => 'date',
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

    /** @var array */
    protected $attributes = array(
        'acc_id'               => null,
        'name'                 => null,
        'descr'                => null,
        'reward'               => '10000',
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
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'label',
        'description',
        'reward',
        'start_date',
        'finish_date',
        'start_time',
        'finish_time',
        'country',
        'city',
        'category_id',
        'max_count',
        'max_for_user',
        'max_per_day',
        'max_for_user_per_day',
        'user_level_min',
        'latitude',
        'longitude',
        'radius'
    ];

    /** @var array */
    protected $maps = [
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

    /**
     * @var array
     */
    public static $visibleFields = [
        'acc_id',
        'name',
        'descr',
        'dt_start',
        'dt_finish',
        'tm_start',
        'tm_finish',
        'country',
        'city',
        'categ',
        'lat',
        'lng',
        'radius'
    ];

    /** @return BelongsTo */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
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
     * @return float
     */
    public function getRewardAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
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

    /** @return Carbon */
    public function getFinishDate(): Carbon
    {
        return $this->finish_date;
    }

    /** @return Carbon */
    public function getStartTime(): Carbon
    {
        return $this->start_time;
    }

    /** @return Carbon */
    public function getFinishTime(): Carbon
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

    /** @return double */
    public function getLatitude(): double
    {
        return $this->latitude;
    }

    /** @return double */
    public function getLongitude(): double
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
     * @param Builder $builder
     * @param int $accountId
     * @return Builder
     */
    public function scopeAccountOffers(Builder $builder, int $accountId): Builder
    {
        return $builder->where('account_id', $accountId);
    }

    /**
     * @param Builder $builder
     * @param string $lat
     * @param string $lng
     * @param int $radius
     * @return Builder
     */
    public function scopeFilterByPosition(Builder $builder, string $lat, string $lng, int $radius): Builder
    {
        $radius = $radius * 1000; //kilometers
        return $builder->whereRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((lat - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(lat * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((lng - %2$s) * 
        PI()/180 / 2), 2)))) < (radius + %3$d)',
            DB::connection()->getPdo()->quote($lat),
            DB::connection()->getPdo()->quote($lng),
            $radius));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function redeem(User $user)
    {
        $user->get(); // just sample
        //return $this->redemptions()->create(['user_id' => $user->id]);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isOwner(User $user): bool
    {
        return $user->equals($this->getOwner());
    }
}
