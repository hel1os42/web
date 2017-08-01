<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.07.2017
 * Time: 17:55
 */

namespace App\Models\NauModels;

use App\Models\NauModels\Traits\Nau;
use App\Models\Support\NauCollection;
use Illuminate\Database\Eloquent\Model;

class NauModel extends Model
{
    use Nau;

    /**
     * @var string
     */
    protected $connection = 'pgsql_nau';

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.uO';

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array $models
     *
     * @return NauCollection
     */
    public function newCollection(array $models = [])
    {
        return new NauCollection($models);
    }
}
