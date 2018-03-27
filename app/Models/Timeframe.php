<?php

namespace App\Models;

use App\Helpers\Constants;
use App\Models\NauModels\Offer;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Timeframes
 * @package App\Models
 *
 * @property int       $days
 * @property \DateTime $from
 * @property \DateTime $to
 */
class Timeframe extends Model
{
    use Uuids;

    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table = 'timeframes';

        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'id' => 'uuid',
            'offer_id' => 'uuid',
            'from' => 'time',
            'to' => 'time',
        ];

        $this->fillable = [
            'offer_id',
            'from',
            'to',
            'days',
        ];

        $this->hidden = [
            'id',
            'offer_id',
        ];

        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /**
     * @return BelongsTo
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * @param string $fromTime
     *
     * @throws \InvalidArgumentException
     */
    public function setFromAttribute($fromTime)
    {
        $this->attributes['from'] = Carbon::createFromFormat(Constants::TIME_FORMAT, $fromTime)
                                          ->setTimezone('UTC')
                                          ->toTimeString();
    }

    /**
     * @param string $toTime
     *
     * @throws \InvalidArgumentException
     */
    public function setToAttribute($toTime)
    {
        $this->attributes['to'] = Carbon::createFromFormat(Constants::TIME_FORMAT, $toTime)
                                          ->setTimezone('UTC')
                                          ->toTimeString();
    }
}
