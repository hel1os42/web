<?php

namespace App\Models\NauModels;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class NauModel
 * @package App\Models\NauModels
 */
class NauModel extends Model
{
    use Eloquence, Mappable;

    const DATE_FORMAT = 'Y-m-d H:i:s.uO';

    /**
     * Mapped attributes
     *
     * @var array
     */
    protected $maps = [];

    /**
     * NauModel constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = 'pgsql_nau';
        $this->dateFormat = self::DATE_FORMAT;

        parent::__construct($attributes);
    }


    /**
     * @param Builder $query
     *
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performUpdate(Builder $query)
    {
        return $this->fireModelEvent('updated') !== false;
    }

    /**
     * @param Builder $query
     *
     * @return bool
     * @SuppressWarnings("unused")
     */
    /*protected function performInsert(Builder $query)
    {
        return $this->fireModelEvent('creating') !== false;
    }*/
}
