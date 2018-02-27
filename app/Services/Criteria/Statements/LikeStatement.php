<?php

namespace App\Services\Criteria\Statements;

/**
 * Class LikeStatement
 * @package App\Services\Criteria\Statements
 */
class LikeStatement extends WhereStatement
{
    /**
     * @return SearchStatement
     */
    public function init(): SearchStatement
    {
        $this->value = ($this->operator == "like" || $this->operator == "ilike") ? "%{$this->value}%" : $this->value;

        return $this;
    }
}
