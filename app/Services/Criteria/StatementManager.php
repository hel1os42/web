<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 21.11.17
 * Time: 4:50
 */

namespace App\Services\Criteria;

/**
 * Interface StatementManager
 * @package App\Services\Criteria
 */
interface StatementManager
{
    public function init(CriteriaData $criteriaData);

    public function apply($builder);
}
