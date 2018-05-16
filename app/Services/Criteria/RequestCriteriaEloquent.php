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
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CriteriaInterfaceEloquent
 * @package App\Services\Criteria
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

        // then we can apply parts or query to our builder
        $this->model = $this->model->where(function ($query) use ($statementManager) {
            $statementManager->apply($query);
        });

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
     * @return $this
     */
    protected function applyFilterForUser()
    {
        if (null === $this->criteriaData->getSearchValues()
            || !isset($this->criteriaData->getSearchValues()['availableForUser'])) {
            return $this;
        }

        $userId = $this->criteriaData->getSearchValues()['availableForUser'];

        if (auth()->user()->isAdmin()) {
            $this->model = $this->filterForUserByAdmin($userId);
        } elseif (auth()->user()->isAgent()) {
            $this->model = $this->filterForUserByAgent($userId);
        }

        return $this;
    }


    /**
     * @param string $userId
     * @return Model
     */
    protected function filterForUserByAdmin($userId)
    {
        $model = $this->model;
        $user  = app(User::class)->find($userId);

        if ($user->isChiefAdvertiser() && count($parentId = $user->parents()->pluck('id'))) {

            $parent   = app(User::class)->find($parentId)->first();
            $chiefIds = $this->getChildrenChiefsIds($parent, $userId);

            $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
                ->where(function(Builder $query) use ($parentId) {
                    $query->where('users_parents.parent_id', $parentId)
                        ->orWhere('users_parents.parent_id', null);
                });

            $this->excludeUsersIds($model, $chiefIds);

            return $model;
        }

        $model->leftJoin('users_parents', 'users.id', '=', 'users_parents.user_id')
            ->where('users_parents.user_id', null);

        return $model;
    }

    /**
     * @param string $userId
     * @return Model
     */
    protected function filterForUserByAgent($userId)
    {
        $model = $this->model;

        $chiefIds = $this->getChildrenChiefsIds(auth()->user(), $userId);
        $this->excludeUsersIds($model, $chiefIds);

        return $model;
    }

    /**
     * @param $query
     * @param $ids
     */
    protected function excludeUsersIds(&$query, $ids)
    {
        $query->whereNotIn('id', function(QueryBuilder $query) use ($ids) {
            $query->select('user_id')
                ->from('users_parents AS up')
                ->whereIn('up.parent_id', $ids);
        });
    }


    /**
     * @param User $parent
     * @param string $currentChiefId
     * @return Collection
     */
    protected function getChildrenChiefsIds(User $parent, $currentChiefId): Collection
    {
        return $parent->children()
            ->with('roles')
            ->whereHas('roles', function(Builder $query) {
                $query->where('roles.name', Role::ROLE_CHIEF_ADVERTISER);
            })
            ->pluck('id')
            ->reject(function($value) use ($currentChiefId) {
                return $value == $currentChiefId;
            });
    }
}
