<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

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
 */
class Redemption extends Model
{
    use ReadOnlyTrait;

    /** @var string */
    private $table = "redemption";

    /** @var array */
    private $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    private $primaryKey = 'id';

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

    /** @return float */
    public function getAmount(): float
    {
        $coinDivider = config('models_config.coin_divider');
        return round($this->amount * pow(0.1, $coinDivider), $coinDivider);
    }

    /** @return float */
    public function getFee(): float
    {
        $coinDivider = config('models_config.coin_divider');
        return round($this->fee * pow(0.1, $coinDivider), $coinDivider);
    }

    /** @return Carbon */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /** @return Carbon */
    public function getUpdatedAt(): Carbon
    {
        return Carbon::parse($this->updated_at);
    }
}