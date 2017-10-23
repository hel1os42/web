<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 20.10.17
 * Time: 14:32
 */

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class MappableRequestCriteriaEloquent
 * @package App\Repositories\Criteria
 */
class MappableRequestCriteriaEloquent extends RequestCriteria implements MappableRequestCriteria
{
    protected $model;

    public function apply($model, RepositoryInterface $repository)
    {
        $this->model = $model;

        return parent::apply($model, $repository);
    }


    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = parent::parserSearchData($search);

        return count($searchData) > 0 ? $this->mapFields($searchData) : $searchData;
    }

    /**
     * @param array      $fields
     * @param array|null $searchFields
     *
     * @return array
     * @throws \Exception
     */
    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {
        $fields = parent::parserFieldsSearch($fields, $searchFields);

        return count($fields) > 0 ? $this->mapFields($fields) : $fields;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    protected function mapFields(array $fields): array
    {
        $model        = $this->model;
        $mappedFields = [];
        foreach ($fields as $field => $criteria) {
            $mappedField = $model->hasMapping($field)
                ? $model->getMappingForAttribute($field)
                : $field;

            $mappedFields[$mappedField] = $criteria;
        }

        return $mappedFields;
    }
}
