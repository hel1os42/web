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
     * @param Builder $query
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function apply(Builder $query) : Builder
    {
        if ($this->isValid() === false) {
            return $query;
        };

        $this->convertValueToArray();

        /** @var Builder $query */
        if (null === $this->relation) {
            $modelTableName = $query->getModel()->getTable();

            return $query->whereBetween($modelTableName . '.' . $this->field, $this->value, $this->searchJoin);
        }


        $callback = function ($query) {
            /** @var Builder $query */
            $query->whereBetween($this->field, $this->value);
        };

        return $this->whereHasForDiffConnections($query, $callback);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return parent::isValid()
            && preg_match('/^(\d+)\,(\d+)$/', $this->value) == true;
    }

    /**
     * return void
     */
    private function convertValueToArray()
    {
        $this->value = explode(',', $this->value);
    }
}
