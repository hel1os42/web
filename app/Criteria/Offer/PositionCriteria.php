<?php

namespace App\Criteria\Offer;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PositionCriteria
 *
 * @package namespace App\Criteria;
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class PositionCriteria implements CriteriaInterface
{
    /**
     * @var string/null
     */
    private $latitude;

    /**
     * @var string|null
     */
    private $longitude;

    /**
     * @var int|null
     */
    private $radius;

    /**
     * PositionCriteria constructor.
     *
     * @param string|null $latitude
     * @param string|null $longitude
     * @param int|null    $radius
     */
    public function __construct(string $latitude = null, string $longitude = null, int $radius = null)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        $this->radius    = $radius;
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
        return $model->filterByPosition(
            $this->latitude,
            $this->longitude,
            $this->radius
        );
    }
}
