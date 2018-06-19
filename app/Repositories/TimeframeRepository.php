<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 10.10.17
 * Time: 13:40
 */

namespace App\Repositories;

use App\Models\NauModels\Offer;
use App\Models\Timeframe;
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
     * @return Collection
     */
    public function createManyForOffer(array $timeframes, Offer $offer): Collection;

    /**
     * @param array $timeframes
     * @param Offer $offer
     *
     * @return Collection
     */
    public function replaceManyForOffer(array $timeframes, Offer $offer): Collection;

    /**
     * @param Offer $offer
     * @param int   $days
     *
     * @return Timeframe|null
     */
    public function findByOfferAndDays(Offer $offer, int $days): ?Timeframe;
}
