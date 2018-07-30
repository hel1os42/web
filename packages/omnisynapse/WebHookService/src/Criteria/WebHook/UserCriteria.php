<?php

namespace OmniSynapse\WebHookService\Criteria\WebHook;

use Illuminate\Contracts\Auth\Authenticatable;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserCriteria
 * @package namespace App\Criteria;
 */
class UserCriteria implements CriteriaInterface
{

    /**
     * @var Authenticatable
     */
    private $user;

    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
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
        return $model->where('user_id', $this->user->getAuthIdentifier());
    }
}
