<?php

namespace App\Models;

use App\Models\Timeframes\HasWeekdays;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timeframes
 * @package App\Models
 */
class Timeframes extends Model
{
    use HasWeekdays;

    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table = 'timeframes';

        $this->primaryKey = 'id';

        $this->casts = [
            'id' => 'uuid',
            'offer_id' => 'uuid',
            'from' => 'time',
            'to' => 'time',
        ];

        $this->appends = [
            'weekdays'
        ];

        parent::__construct($attributes);
    }

}

