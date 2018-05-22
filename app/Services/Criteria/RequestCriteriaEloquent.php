<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 26.10.17
 * Time: 1:56
 */

namespace App\Services\Criteria;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CriteriaInterfaceEloquent
 * @package App\Services\Criteria
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RequestCriteriaEloquent implements CriteriaInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var CriteriaData
     */
    protected $criteriaData;

    protected $availableForUserId;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Builder|\Illuminate\Database\Eloquent\Model $model
     * @param RepositoryInterface                         $repository
     *
     * @return Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $this->model      = $model;
        $fieldsSearchable = $repository->getFieldsSearchable();

        $this->criteriaData = app(CriteriaData::class)
            ->setFieldsSearchable($fieldsSearchable)
            ->init();

        $this->availableForUserId = $this->request->get('availableForUser');

        $this->applySearch()
             ->applyOrderBy()
             ->applyFilter()
             ->applyFilterForUser()
             ->applyWith();

        return $this->model;
    }

    /**
     * @return CriteriaInterface
     * @throws \Exception
     */
    protected function applySearch(): CriteriaInterface
    {
        // if we don't have search parameter in request or array with allowed search fields in repository
        // - we can skip search applying
        if (null === $this->criteriaData->getSearchValues()
            || !is_array($this->criteriaData->getFieldsSearchable())
            || 0 == count($this->criteriaData->getFieldsSearchable())) {
            return $this;
        }

        // statement manager creates SearchStatement objects for each search field
        $statementManager = app(StatementManager::class);
        $statementManager->init($this->criteriaData);

        $roleStatement = $statementManager->getAndRemoveStatementByRelation('roles');

        if ($statementManager->hasStatements()) {
            // then we can apply parts or query to our builder
            $this->model = $this->model->where(function ($query) use ($statementManager) {
                $statementManager->apply($query);
            });
        }

        if (null !== $roleStatement) {
            $roleStatement->setSearchJoin('and');
            $roleStatementManager = app(StatementManager::class);
            $roleStatementManager->push($roleStatement);

            $this->model = $this->model->where(function ($query) use ($roleStatementManager) {
                $roleStatementManager->apply($query);
            });
        }

        return $this;
    }

    /**
     * @return CriteriaInterface
     * @throws \InvalidArgumentException
     */
    protected function applyOrderBy(): CriteriaInterface
    {
        if (null === $orderBy = $this->criteriaData->getOrderBy()) {
            return $this;
        }

        $sortedBy = $this->criteriaData->getSortedBy();

        // next code was copy-pasted from prettus/l5-repository lib

        $split = explode('|', $orderBy);
        if (count($split) > 1) {
            /*
             * ex.
             * products|description -> join products on current_table.product_id = products.id order by description
             *
             * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
             * by products.description (in case both tables have same column name)
             */
            $table      = $this->model->getModel()->getTable();
            $sortTable  = $split[0];
            $sortColumn = $split[1];

            $split = explode(':', $sortTable);
            if (count($split) > 1) {
                $sortTable = $split[0];
                $keyName   = $table . '.' . $split[1];
            } else {
                /*
                 * If you do not define which column to use as a joining column on current table, it will
                 * use a singular of a join table appended with _id
                 *
                 * ex.
                 * products -> product_id
                 */
                $prefix  = rtrim($sortTable, 's');
                $keyName = $table . '.' . $prefix . '_id';
            }

            $this->model = $this->model
                ->leftJoin($sortTable, $keyName, '=', $sortTable . '.id')
                ->orderBy($sortColumn, $sortedBy)
                ->addSelect($table . '.*');
        } else {
            $this->model = $this->model->orderBy($orderBy, $sortedBy);
        }

        return $this;
    }

    /**
     * @return CriteriaInterface
     */
    protected function applyFilter(): CriteriaInterface
    {
        if (null === $filter = $this->criteriaData->getFilter()) {
            return $this;
        }

        $this->model = $this->model->select(explode(';', $filter));

        return $this;
    }

    /**
     * @return CriteriaInterface
     */
    protected function applyWith(): CriteriaInterface
    {
        if (null === $with = $this->criteriaData->getWith()) {
            return $this;
        }

        $this->model = $this->model->with(explode(';', $with));

        return $this;
    }

    /**
     * @return CriteriaInterface
     */
    protected function applyFilterForUser(): CriteriaInterface
    {
        if (null === $this->availableForUserId || !$this->model->getModel() instanceof User) {
            return $this;
        }

        if (auth()->user()->isAdmin()) {
            $this->filterForUserByAdmin();
        } elseif (auth()->user()->isAgent()) {
            $this->filterForUserByAgent();
        }

        return $this;
    }

    protected function filterForUserByAdmin()
    {
        $model = $this->model;
        $user  = app(User::class)->find($this->availableForUserId);

        if (null !== $user && $user->isChiefAdvertiser() && count($parentId = $user->parents()->pluck('id'))) {
            $parent   = app(User::class)->find($parentId)->first();
            $chiefIds = $this->getChildrenIdsByRole($parent, Role::ROLE_CHIEF_ADVERTISER);

            $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
                ->where(function(Builder $query) use ($parentId) {
                    $query->where('users_parents.parent_id', $parentId)
                        ->orWhere('users_parents.parent_id', null);
                });

            $this->excludeChildrenByParentsIds($model, $chiefIds);

            $this->model = $model;
            return;
        }

        $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
            ->where('users_parents.user_id', null)
            ->orWhere('users_parents.parent_id', $this->availableForUserId);

        $this->model = $model;
    }

    protected function filterForUserByAgent()
    {
        $model = $this->model;

        $chiefIds = $this->getChildrenIdsByRole(auth()->user(), Role::ROLE_CHIEF_ADVERTISER);
        $this->excludeChildrenByParentsIds($model, $chiefIds);

        $this->model = $model;
    }

    /**
     * @param $query
     * @param array|Collection $ids
     */
    protected function excludeChildrenByParentsIds(&$query, $ids)
    {
        $query->whereNotIn('id', function(QueryBuilder $query) use ($ids) {
            $query->select('user_id')
                ->from('users_parents AS up')
                ->whereIn('up.parent_id', $ids);
        });
    }


    /**
     * @param User $parent
     * @param string $roleName
     * @return Collection
     */
    protected function getChildrenIdsByRole(User $parent, string $roleName): Collection
    {
        return $parent->children()
            ->with('roles')
            ->whereHas('roles', function(Builder $query) use ($roleName) {
                $query->where('roles.name', $roleName);
            })
            ->pluck('id')
            ->reject(function($value) {
                return $value == $this->availableForUserId;
            });
    }
}
