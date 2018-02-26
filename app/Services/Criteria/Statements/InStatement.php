<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class InStatement
 * @package App\Services\Criteria\Statements
 */
class InStatement extends AbstractStatement
{
    public function init(): SearchStatement
    {
        // laravel expects array in "where in" query clause
        if (!is_array($this->value)) {
            $this->value = [$this->value];
        }

        return $this;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function apply(Builder $query): Builder
    {
        if (null === $this->relation) {
            $modelTableName = $query->getModel()->getTable();

            return $query->whereIn($modelTableName . '.' . $this->field, $this->value, $this->searchJoin);
        }

        return $query->whereHas($this->relation, function ($query) {
            $query->whereIn($this->field, $this->value, $this->searchJoin);
        });
    }
}
