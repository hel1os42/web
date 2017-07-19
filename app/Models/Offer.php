<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 * Class Offer
 * @package App
 *
 * @property string id
 * @property int acc_id
 * @property string name
 * @property string descr
 * @property float reward
 * @property string status
 * @property Carbon dt_start
 * @property Carbon dt_finish
 * @property Carbon tm_start
 * @property Carbon tm_finish
 * @property string country
 * @property string city
 * @property string categ
 * @property int max_count
 * @property int max_for_user
 * @property int max_per_day
 * @property int max_for_user_per_day
 * @property int min_level
 * @property double lat
 * @property double lng
 * @property int radius
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Offer extends Model
{
    use ReadOnlyTrait;

    /** @var string */
    private $table = "offer";

    /** @var array */
    private $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    private $primaryKey = 'id';

    /** @var array */
    protected $casts = [
        'id'                    => 'string',
        'acc_id'                => 'integer',
        'name'                  => 'string',
        'descr'                 => 'string',
        'reward'                => 'float',
        'status'                => 'string',
        'dt_start'              => 'date',
        'dt_finish'             => 'date',
        'tm_start'              => 'datetime',
        'tm_finish'             => 'datetime',
        'country'               => 'string',
        'city'                  => 'string',
        'categ'                 => 'string',
        'max_count'             => 'integer',
        'max_for_user'          => 'integer',
        'max_per_day'           => 'integer',
        'max_for_user_per_day'  => 'integer',
        'min_level'             => 'integer',
        'lat'                   => 'double',
        'lng'                   => 'double',
        'radius'                => 'integer',
    ];

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return int */
    public function getAccId(): int
    {
        return $this->acc_id;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return string */
    public function getDescr(): string
    {
        return $this->descr;
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
    public function getDtStart(): Carbon
    {
        return Carbon::parse($this->dt_start);
    }

    /** @return Carbon */
    public function getDtFinish(): Carbon
    {
        return Carbon::parse($this->dt_finish);
    }

    /** @return Carbon */
    public function getTmStart(): Carbon
    {
        return Carbon::parse($this->tm_start);
    }

    /** @return Carbon */
    public function getTmFinish(): Carbon
    {
        return Carbon::parse($this->tm_finish);
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
    public function getCateg(): string
    {
        return $this->categ;
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
    public function getMinLevel(): int
    {
        return $this->min_level;
    }

    /** @return double */
    public function getLat(): double
    {
        return $this->lat;
    }

    /** @return double */
    public function getLng(): double
    {
        return $this->lng;
    }

    /** @return int */
    public function getRadius(): int
    {
        return $this->radius;
    }

    // TODO: relation with account
}