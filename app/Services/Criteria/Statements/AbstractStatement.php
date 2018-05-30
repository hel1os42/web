<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class AbstractStatement
 * @package App\Services\Criteria\Statements
 */
abstract class AbstractStatement implements SearchStatement
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string|array
     */
    protected $value;

    /**
     * @var string
     */
    protected $searchJoin = 'or';

    /**
     * @var null|string
     */
    protected $relation = null;

    /**
     * @param $field
     *
     * @return SearchStatement
     */
    public function setField($field): SearchStatement
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param $operator
     *
     * @return SearchStatement
     */
    public function setOperator($operator): SearchStatement
    {
        $this->operator = trim(strtolower($operator));

        return $this;
    }

    /**
     * @param $searchJoin
     *
     * @return SearchStatement
     */
    public function setSearchJoin($searchJoin): SearchStatement
    {
        $this->searchJoin = $searchJoin;

        return $this;
    }

    /**
     * @param $value
     *
     * @return SearchStatement
     */
    public function setValue($value): SearchStatement
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return SearchStatement
     */
    public function initialize(): SearchStatement
    {
        $this->detectRelation()
             ->init();

        return $this;
    }

    /**
     * @return SearchStatement
     */
    public function init(): SearchStatement
    {
        return $this;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    abstract public function apply(Builder $query): Builder;

    /**
     * @return $this
     */
    protected function detectRelation()
    {
        if (stripos($this->field, '.')) {
            $explode        = explode('.', $this->field);
            $this->field    = array_pop($explode);
            $this->relation = implode('.', $explode);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return null !== $this->value ? true : false;
    }
}
