<?php

namespace App\Services\Criteria;

use App\Services\Criteria\Exceptions\FieldsSearchableNotFoundException;
use Illuminate\Http\Request;

/**
 * Class CriteriaDataImpl
 * @package App\Services\Criteria
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class CriteriaDataImpl implements CriteriaData
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Searchable fields list configured in repository
     *
     * @var null|array
     */
    protected $fieldsSearchable = null;

    /**
     * Fields in which research should be carried out
     *     ?search=lorem&searchFields=name;email
     *     ?search=lorem&searchFields=name:like;email
     *     ?search=lorem&searchFields=name:like
     *
     * @var null|array
     */
    protected $searchFields = null;

    /**
     * Searched value
     *     ?search=lorem
     *
     * @var null|array
     */
    protected $searchValues = null;

    /**
     * Specifies the search method (AND / OR), by default the application searches each parameter with OR
     *     ?search=lorem&searchJoin=and
     *     ?search=lorem&searchJoin=or
     *
     * @var string
     */
    protected $searchJoin = 'or';

    /**
     * Order By
     *     ?search=lorem&orderBy=id
     *
     * @var string|null
     */
    protected $orderBy = null;

    /**
     * Sorting
     *     ?search=lorem&orderBy=id&sortedBy=asc
     *     ?search=lorem&orderBy=id&sortedBy=desc
     *
     * @var string|null
     */
    protected $sortedBy = null;

    /**
     * Fields that must be returned to the response object
     *     ?search=lorem&filter=id,name
     *
     * @var string|null
     */
    protected $filter = null;

    /**
     * Relations that should be loaded
     *     http://prettus.local/?search=lorem&with=ipsum
     *
     * @var null|string
     */
    protected $with = null;

    /**
     * Filterable fields list
     *
     * @var null|array
     */
    protected $whereFiltersFilterable = null;
    /**
     * Filtered value
     *     ?whereFilters=role:name;
     *
     * @var null|array
     */
    protected $whereFilters = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return CriteriaData
     * @throws FieldsSearchableNotFoundException
     */
    public function init(): CriteriaData
    {
        if (null === $this->fieldsSearchable) {
            throw new FieldsSearchableNotFoundException('CriteriaData: fieldsSearchable is null');
        }

        $this->searchFields = $this->searchFieldsParam();

        $this->initSearchValue()
             ->initSearchFields()
             ->initWhereFilters();

        $this->searchJoin = strtolower($this->searchJoinParam()) === 'and' ? 'and' : 'or';
        $this->orderBy    = $this->orderByParam();
        $this->sortedBy   = $this->sortedByParam();
        $this->filter     = $this->filterParam();
        $this->with       = $this->withParam();

        return $this;
    }

    /**
     * Fields in which research should be carried out
     *     ?search=lorem&searchFields=name;email
     *     ?search=lorem&searchFields=name:like;email
     *
     * @return CriteriaData
     */
    protected function initSearchFields(): CriteriaData
    {
        $strSearchFields = $this->searchFieldsParam();

        if (null === $strSearchFields) {
            return $this->initSearchFieldsFromSearchParam();
        }

        $arrSearchFields = [];

        $explodedSearchFields = explode(';', $strSearchFields);

        foreach ($explodedSearchFields as $value) {
            $exploded = explode(':', $value);
            $field    = $exploded[0];
            // if we have both: field and operator
            if (array_key_exists(1, $exploded)) {
                $arrSearchFields[$field] = $exploded[1];
                continue;
            }
            // we have only field name. so we should use default operator
            if (array_key_exists($field, $this->fieldsSearchable)) {
                $arrSearchFields[$field] = $this->fieldsSearchable[$field];
            }
        }

        $this->searchFields = $arrSearchFields;

        return $this;
    }

    /**
     * @return CriteriaData
     */
    protected function initSearchFieldsFromSearchParam(): CriteriaData
    {
        if (null === $this->searchValues) {
            $this->initSearchValue();
        }

        if (is_array($this->fieldsSearchable) && is_array($this->searchValues)) {
            $this->searchFields = array_intersect_key($this->fieldsSearchable, $this->searchValues);
        }

        return $this;
    }

    /**
     * Searchable fields list configured in repository
     *
     * @param array|null $fieldsSearchable
     *
     * @return CriteriaData
     */
    public function setFieldsSearchable(?array $fieldsSearchable): CriteriaData
    {
        $this->fieldsSearchable = $fieldsSearchable;

        return $this;
    }

    /**
     * @param $paramsStr
     * @param string $variableName
     */
    protected function parseParams($paramsStr, string $variableName) {
        if (false !== stripos($paramsStr, ';') || false !== stripos($paramsStr, ':')) {
            $params = explode(';', $paramsStr);
            foreach ($params as $param) {
                if (false === $delimiterPosition = stripos($param, ':')) {
                    continue;
                }

                $field                       = substr($param, 0, $delimiterPosition);
                $values                      = substr($param, ++$delimiterPosition, strlen($param));
                $this->$variableName[$field] = (false === stripos($values, '|'))
                    ? $values
                    : explode('|', $values);
            }
        }
    }

    /**
     * @return CriteriaData
     */
    protected function initSearchValue(): CriteriaData
    {
        $this->parseParams($this->searchParam(), 'searchValues');

        return $this;
    }

    /**
     * @return string|null
     */
    protected function searchParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.search', 'search'),
            null
        );
    }

    /**
     * @return string|null
     */
    protected function searchFieldsParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.searchFields', 'searchFields'),
            null
        );

    }

    /**
     * @return string|null
     */
    protected function searchJoinParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.searchJoin', 'searchJoin'),
            null
        );
    }

    /**
     * @return string|null
     */
    protected function orderByParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.orderBy', 'orderBy'),
            null
        );
    }

    /**
     * @return string|null
     */
    protected function sortedByParam(): string
    {
        return $this->request->get(
            config('repository.criteria.params.sortedBy', 'sortedBy'),
            'asc'
        );
    }

    /**
     * @return string|null
     */
    protected function filterParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.filter', 'filter'),
            null
        );
    }

    /**
     * @return string|null
     */
    protected function withParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.with', 'with'),
            null
        );
    }

    /**
     * @return null|string
     */
    protected function whereFiltersParam(): ?string
    {
        return $this->request->get(
            config('repository.criteria.params.whereFilters', 'whereFilters'),
            null
        );
    }

    /**
     * @return CriteriaData
     */
    protected function initWhereFilters(): CriteriaData
    {
        $filters = $this->whereFiltersParam();

        if (null === $this->whereFiltersFilterable || null === $filters) {
            return $this;
        }

        $this->parseParams($filters, 'whereFilters');

        $this->whereFilters = array_filter($this->whereFilters, function($field) {
            return (true === array_key_exists($field, $this->whereFiltersFilterable));
        }, ARRAY_FILTER_USE_KEY);

        return $this;
    }

    /**
     * @param array $whereFiltersFilterable
     * @return CriteriaData
     */
    public function setWhereFiltersFilterable(array $whereFiltersFilterable): CriteriaData
    {
        $this->whereFiltersFilterable = $whereFiltersFilterable;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getWhereFiltersFilterable(): ?array
    {
        return $this->whereFiltersFilterable;
    }

    /**
     * @return array|null
     */
    public function getFieldsSearchable()
    {
        return $this->fieldsSearchable;
    }

    /**
     * @return array|null
     */
    public function getSearchFields()
    {
        return $this->searchFields;
    }

    /**
     * @return string
     */
    public function getSearchJoin()
    {
        return $this->searchJoin;
    }

    /**
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return string|null
     */
    public function getSortedBy()
    {
        return $this->sortedBy;
    }

    /**
     * @return string|null
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string|null
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * @return array|null
     */
    public function getWhereFilters(): ?array
    {
        return $this->whereFilters;
    }

    /**
     * @return array|null
     */
    public function getSearchValues(): ?array
    {
        return $this->searchValues;
    }

    /**
     * @return string|array|null
     */
    public function getSearchValueByField(string $field)
    {
        return (!is_array($this->searchValues) || !array_key_exists($field, $this->searchValues))
            ? null
            : $this->searchValues[$field];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'fieldsSearchable' => $this->getFieldsSearchable(),
            'searchValues'     => $this->getSearchValues(),
            'searchFields'     => $this->getSearchFields(),
            'searchJoin'       => $this->getSearchJoin(),
            'orderBy'          => $this->getOrderBy(),
            'sortedBy'         => $this->getSortedBy(),
            'filter'           => $this->getFilter(),
            'with'             => $this->getWith(),
            'whereFilters'     => $this->getWhereFilters(),
        ];
    }
}
