<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface SearchStatement
 * @package App\Services\Criteria\Statements
 */
interface SearchStatement
{
    /**
     * @param $field
     *
     * @return SearchStatement
     */
    public function setField($field): SearchStatement;

    /**
     * @param $operator
     *
     * @return SearchStatement
     */
    public function setOperator($operator): SearchStatement;

    /**
     * @param $searchJoin
     *
     * @return SearchStatement
     */
    public function setSearchJoin($searchJoin): SearchStatement;

    /**
     * @param $value
     *
     * @return SearchStatement
     */
    public function setValue($value): SearchStatement;

    /**
     * @return SearchStatement
     */
    public function initialize(): SearchStatement;

    /**
     * @return SearchStatement
     */
    public function init(): SearchStatement;

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function apply(Builder $query): Builder;

    /**
     * @return bool
     */
    public function isValid(): bool;
}
