<?php

namespace App\Models\NauModels;

use App\Traits\NauObj;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasNau;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class NauModel
 * @package App\Models\NauModels
 */
class NauModel extends Model
{
    use HasNau;
    use NauObj, Eloquence, Mappable;

    /**
     * @var string
     */
    protected $connection = 'pgsql_nau';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.uO';
}
