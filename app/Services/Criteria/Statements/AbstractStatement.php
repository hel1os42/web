<?php

namespace App\Services\Criteria\Statements;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\Relation;

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

    /**
     * The method allows using the Eloquent whereHas for different connections
     * @param Builder   $query
     * @param \Closure  $callback
     * @return Builder
     */
    public function whereHas(Builder $query, \Closure $callback): Builder
    {
        /** @var Builder $query */
        $relation      = $query->getRelation($this->relation);
        $relationModel = $relation->getModel();

        // similar connections
        if (false === $this->isDiffConnections($query, $relationModel)) {
            $method = 'or' === $this->searchJoin ? 'orWhereHas' : 'whereHas';

            return $query->$method($this->relation, $callback);
        }

        // different connections
        if (false !== $foreignKey = $this->getForeignKeyName($relation)) {
            $ids = $relationModel->where($callback)->pluck($foreignKey);

            return $query->whereIn('id', $ids, $this->searchJoin);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param Model   $relation
     * @return bool
     */
    public function isDiffConnections(Builder $query, Model $relationModel): bool
    {
        return $relationModel->getConnection()->getName() !== $query->getConnection()->getName();
    }

    /**
     * @param Relation $relation
     * @return bool|string
     */
    protected function getForeignKeyName(Relation $relation): ?string
    {
        if ($relation instanceof HasOneOrMany) {
            return $relation->getForeignKeyName();
        }

        return false;
    }
}
