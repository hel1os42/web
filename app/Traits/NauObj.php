<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait NauObj
 * @package App\Traits
 */
trait NauObj
{
    /**
     * @param Builder $query
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performUpdate(Builder $query)
    {
        return $this->fireModelEvent('updated') !== false;
    }

    /**
     * @param Builder $query
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performInsert(Builder $query)
    {
        return $this->fireModelEvent('creating') !== false;
    }
}
