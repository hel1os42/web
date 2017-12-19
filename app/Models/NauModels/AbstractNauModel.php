<?php

namespace App\Models\NauModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class AbstractNauModel
 * @package App\Models\NauModels
 */
abstract class AbstractNauModel extends Model
{
    use Eloquence, Mappable;

    const DATE_FORMAT = 'Y-m-d H:i:s.uO';
    const TIME_FORMAT = 'H:i:s.uO';

    /**
     * Mapped attributes
     *
     * @var array
     */
    protected $maps = [];

    /**
     * AbstractNauModel constructor.
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

    public function asDateTime($value)
    {
        try {
            $returnValue = parent::asDateTime($value);
        } catch (\InvalidArgumentException $e) {
            $returnValue = Carbon::parse($value);
        }

        return $returnValue;
    }

    /**
     * @param Builder $query
     *
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performUpdate(Builder $query)
    {
        return $this->fireModelEvent('updating') !== false;
    }

    /**
     * @param Builder $query
     *
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performInsert(Builder $query)
    {
        return $this->fireModelEvent('creating') !== false;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return $this->fireModelEvent('deleting') !== false;
    }
}
