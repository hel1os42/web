<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BetweenStatement
 * @package App\Services\Criteria\Statements
 */
class BetweenStatement extends AbstractStatement
{
    /**
     * @return SearchStatement
     */
    public function init(): SearchStatement
    {
        $this->value = explode('|', $this->value);

        return $this;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function apply(Builder $query) : Builder
    {
        /** @var Builder $query */
        if (null === $this->relation) {
            $modelTableName = $query->getModel()->getTable();

            return $query->whereBetween($modelTableName . '.' . $this->field, $this->value, $this->searchJoin);
        }

        return $query->whereHas($this->relation, function ($query) {
            /** @var Builder $query */
            $query->whereBetween($this->field, $this->value, $this->searchJoin);
        });
    }
}
