<?php

namespace App\Models;

use App\Models\NauModels\Offer;
use App\Services\WeekDaysService;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Timeframes
 * @package App\Models
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
}
