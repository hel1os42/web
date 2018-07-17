<?php

namespace App\Services\Criteria;

use App\Services\Criteria\Exceptions\StatementNotFoundException;
use App\Services\Criteria\Exceptions\ClassNotFoundException;
use App\Services\Criteria\Statements\BetweenStatement;
use App\Services\Criteria\Statements\InStatement;
use App\Services\Criteria\Statements\WhereStatement;
use App\Services\Criteria\Statements\LikeStatement;
use App\Services\Criteria\Statements\SearchStatement;

/**
 * Class ApplyStatement
 * @package App\Services\Criteria
 *
 * @SuppressWarnings("CyclomaticComplexity")
 */
class StatementManagerImpl implements StatementManager
{
    protected $items = [];

    protected static $statementClassMap = [
        '='       => WhereStatement::class,
        '<'       => WhereStatement::class,
        '>'       => WhereStatement::class,
        '<='      => WhereStatement::class,
        '>='      => WhereStatement::class,
        'like'    => LikeStatement::class,
        'ilike'   => LikeStatement::class,
        'in'      => InStatement::class,
        'between' => BetweenStatement::class,
    ];

    /**
     * Chose and instantiate correct SearchStatement.
     *
     * @param CriteriaData $criteriaData
     */
    public function init(CriteriaData $criteriaData)
    {
        foreach ($criteriaData->getSearchFields() as $field => $operator) {
            try {
                // hook for default "=" operator.
                if (is_numeric($field)) {
                    $field    = $operator;
                    $operator = "=";
                }
                // selecting new statement, setting its data and pushing into $items property.
                $stmt = $this->newStatement($operator)
                             ->setField($field)
                             ->setOperator($operator)
                             ->setSearchJoin($criteriaData->getSearchJoin())
                             ->setValue($criteriaData->getSearchValueByField($field))
                             ->initialize();
                if ($stmt->isValid()) {
                    $this->push($stmt);
                }
            } catch (StatementNotFoundException $exception) {
                logger()->error($exception->getMessage());
                continue;
            }
        }
    }

    /**
     * @param CriteriaData $criteriaData
     */
    public function initWhereFilters(CriteriaData $criteriaData)
    {
        foreach ($criteriaData->getWhereFilters() as $field => $value) {
            try {
                $operator = $criteriaData->getWhereFiltersFilterable();

                $stmt = $this->newStatement($operator[$field])
                    ->setField($field)
                    ->setOperator($operator[$field])
                    ->setSearchJoin('and')
                    ->setValue($value)
                    ->initialize();

                if ($stmt->isValid()) {
                    $this->push($stmt);
                }
            } catch (StatementNotFoundException $exception) {
                logger()->error($exception->getMessage());
                continue;
            }
        }
    }

    /**
     * @param SearchStatement $stmt
     */
    public function push(SearchStatement $stmt)
    {
        $this->items[] = $stmt;
    }

    /**
     * Runs apply method on each SearchStatement instance from $items property.
     *
     * @param $builder
     *
     * @return mixed
     */
    public function apply($builder)
    {
        foreach ($this->items as $item) {
            $builder = $item->apply($builder);
        }

        return $builder;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Select correct SearchStatement
     *
     * @param string $operator
     *
     * @return SearchStatement
     * @throws ClassNotFoundException
     * @throws StatementNotFoundException
     */
    protected function newStatement(string $operator): SearchStatement
    {
        if (array_key_exists($operator, static::$statementClassMap)) {
            try {
                return new static::$statementClassMap[$operator];
            } catch (\Throwable $exception) {
                throw new ClassNotFoundException(
                    sprintf('%s: %s', get_called_class(), $exception->getMessage()),
                    $exception->getCode(),
                    $exception
                );
            }
        }
        throw new StatementNotFoundException(
            sprintf(
                '%s: Can\'t find Statement for [%s] operator',
                get_called_class(),
                $operator
            )
        );
    }
}
