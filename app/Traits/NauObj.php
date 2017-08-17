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
        if ($this->fireModelEvent('updating') === false) {
            return false;
        }

        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            $this->fireModelEvent('updated', false);
        }

        return true;
    }

    /**
     * @param Builder $query
     * @return bool
     * @SuppressWarnings("unused")
     */
    protected function performInsert(Builder $query)
    {
        if ($this->fireModelEvent('creating') === false) {
            return false;
        }

        if ($this->usesTimestamps()) {
            $this->updateTimestamps();
        }

        if (null !== $this->getKey()) {
            $this->exists             = true;
            $this->wasRecentlyCreated = true;
        }

        $this->fireModelEvent('created', false);

        return true;
    }
}
