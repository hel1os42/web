<?php

namespace App\Models;

use App\Models\Traits\HasNau;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Offer
 * @package App
 *
 * @property string id
 * @property int balance
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
 */
class Offer extends Model
{
    use ReadOnlyTrait;
    use HasNau;

    /** @var string */
    private $table = "offer";

    /** @var array */
    private $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    private $primaryKey = 'id';

    /** @var array */
    protected $maps = [
        'acc_id'    => 'balance',
        'name'      => 'label',
        'descr'     => 'description',
        'dt_start'  => 'start_date',
        'dt_finish' => 'finish_date',
        'tm_start'  => 'start_time',
        'tm_finish' => 'finish_time',
        'categ'     => 'category_id',
        'min_level' => 'user_level_min',
        'lat'       => 'latitude',
        'lng'       => 'longitude',
    ];

    /** @var array */
    protected $casts = [
        'id'                    => 'string',
        'balance'               => 'integer',
        'label'                 => 'string',
        'description'           => 'string',
        'reward'                => 'integer',
        'status'                => 'string',
        'start_date'            => 'date',
        'finish_date'           => 'date',
        'start_time'            => 'datetime',
        'finish_time'           => 'datetime',
        'country'               => 'string',
        'city'                  => 'string',
        'category_id'           => 'string',
        'max_count'             => 'integer',
        'max_for_user'          => 'integer',
        'max_per_day'           => 'integer',
        'max_for_user_per_day'  => 'integer',
        'user_level_min'        => 'integer',
        'latitude'              => 'double',
        'longitude'             => 'double',
        'radius'                => 'integer',
    ];

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return int */
    public function getAccountId(): int
    {
        return $this->balance;
    }

    /** @return BelongsTo */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'balance', 'id');
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
        return Carbon::parse($this->start_date);
    }

    /** @return Carbon */
    public function getFinishDate(): Carbon
    {
        return Carbon::parse($this->finish_date);
    }

    /** @return Carbon */
    public function getStartTime(): Carbon
    {
        return Carbon::parse($this->start_time);
    }

    /** @return Carbon */
    public function getFinishTime(): Carbon
    {
        return Carbon::parse($this->finish_time);
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
}