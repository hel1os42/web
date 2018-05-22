<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 21.11.17
 * Time: 4:50
 */

namespace App\Services\Criteria;

use App\Services\Criteria\Statements\SearchStatement;

/**
 * Interface StatementManager
 * @package App\Services\Criteria
 */
interface StatementManager
{
    public function init(CriteriaData $criteriaData);

    public function apply($builder);

    public function getAndRemoveStatementByRelation(string $name): ?SearchStatement;

    /**
     * @return int
     */
    public function hasStatements(): int;
}
