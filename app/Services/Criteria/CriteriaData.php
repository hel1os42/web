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
     * @return array|null
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
     * @return string|null
     */
    public function getOrderBy();

    /**
     * @return null|string
     */
    public function getSortedBy();

    /**
     * @return string|null
     */
    public function getFilter();

    /**
     * @return string|null
     */
    public function getWith();

    /**
     * @param string $field
     *
     * @return string|array|null
     */
    public function getSearchValueByField(string $field);
}
