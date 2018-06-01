<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 26.10.17
 * Time: 1:56
 */

namespace App\Services\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

        $withArray = explode(';', $with);

        $withArray = array_filter($withArray, function($relation) {
            return false !== method_exists($this->model->getModel(), $relation);
        });

        $this->model = $this->model->with($withArray);

        return $this;
    }
}
