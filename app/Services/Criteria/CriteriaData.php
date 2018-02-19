<?php

namespace App\Services\Criteria;

interface CriteriaData
{
    /**
     * @return CriteriaData
     */
    public function init(): CriteriaData;

    /**
     * @param array|null $fieldsSearchable
     *
     * @return CriteriaData
     */
    public function setFieldsSearchable(?array $fieldsSearchable): CriteriaData;

    /**
     * @return array|null
     */
    public function getFieldsSearchable();

    /**
     * @return mixed
     */
    public function getSearchFields();

    /**
     * @return array|null
     */
    public function getSearchValues(): ?array;

    /**
     * @return null|string
     */
    public function getSearchJoin();

    /**
     * @return mixed
     */
    public function getOrderBy();

    /**
     * @return null|string
     */
    public function getSortedBy();

    /**
     * @return mixed
     */
    public function getFilter();

    /**
     * @return mixed
     */
    public function getWith();

    /**
     * @param string $field
     *
     * @return null|mixed
     */
    public function getSearchValueByField(string $field);
}
