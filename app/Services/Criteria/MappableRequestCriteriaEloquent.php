<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 20.10.17
 * Time: 14:32
 */

namespace App\Services\Criteria;

/**
 * Class MappableRequestCriteriaEloquent
 * @package App\Services\Criteria
 */
class MappableRequestCriteriaEloquent extends RequestCriteriaEloquent implements MappableRequestCriteria
{
    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);
            foreach ($fields as $row) {
                $delimiterPosition  = stripos($row, ':');
                $field              = substr($row, 0, $delimiterPosition);
                $value              = substr($row, ++$delimiterPosition, strlen($row));
                $searchData[$field] = $value;
            }
        }

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
