<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 10.10.17
 * Time: 13:40
 */

namespace App\Repositories;

use App\Models\NauModels\Offer;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TimeframeRepository
 * @package App\Repositories
 */
interface TimeframeRepository extends RepositoryInterface
{
    /**
     * @param array $timeframes
     * @param Offer $offer
     *
     * @return mixed
     */
    public function createMany(array $timeframes, Offer $offer): Collection;
}
