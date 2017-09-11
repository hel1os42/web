<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UserOfferPivot
 * @package App\Models
 */
class UserOfferPivot extends Pivot
{
    protected $dateFormat = 'Y-m-d H:i:s.uO';
}
