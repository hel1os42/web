<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.07.2017
 * Time: 17:55
 */

namespace App\Models\NauModels;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasNau;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class NauModel extends Model
{

    use HasNau;
    use ReadOnlyTrait, Eloquence, Mappable {
        ReadOnlyTrait::save insteadof Eloquence;
    }

    /**
     * @var string
     */
    protected $connection = 'pgsql_nau';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:sO';
}
