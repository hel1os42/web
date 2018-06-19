<?php

namespace App\Criteria\Offer;

use App\Repositories\CategoryRepository;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AccountCriteria
 *
 * @package namespace App\Criteria;
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class AccountCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $accountIds;

    /**
     * AccountCriteria constructor.
     *
     * @param array $accountIds
     */
    public function __construct(array $accountIds)
    {
        $this->accountIds = $accountIds;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->whereIn('account_id', $this->accountIds);
    }
}
