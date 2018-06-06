<?php

namespace App\Criteria\Offer;

use App\Repositories\CategoryRepository;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CategoryCriteria
 *
 * @package namespace App\Criteria;
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class CategoryCriteria implements CriteriaInterface
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var array
     */
    private $categoryIds;

    /**
     * CategoryCriteria constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param array              $categoryIds
     */
    public function __construct(CategoryRepository $categoryRepository, array $categoryIds)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryIds        = $categoryIds;
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
        $categoryIds = $this->categoryRepository->scopeQuery(function ($query) {
            return $query->whereIn('id', $this->categoryIds)
                ->orWhereIn('parent_id', $this->categoryIds)
                ->pluck('id');
        })->all();

        return $model->whereIn('category_id', $categoryIds);
    }
}
