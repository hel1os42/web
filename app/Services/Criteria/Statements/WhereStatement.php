<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class EqualStatement
 * @package App\Services\Criteria\Statements
 */
class WhereStatement extends AbstractStatement
{
    /**
     * @param Builder $query
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function apply(Builder $query): Builder
    {
        /** @var Builder $query */
        if (null === $this->relation) {
            $modelTableName = $query->getModel()->getTable();

            return $query->where($modelTableName . '.' . $this->field, $this->operator, $this->value,
                $this->searchJoin);
        }

        if ('or' === $this->searchJoin) {
            return $query->orWhereHas($this->relation, function ($query) {
                /** @var Builder $query */
                $query->where($this->field, $this->operator, $this->value, 'and');
            });
        }

        return $query->whereHas($this->relation, function ($query) {
            /** @var Builder $query */
            $query->where($this->field, $this->operator, $this->value, 'and');
        });
    }
}
