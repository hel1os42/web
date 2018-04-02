<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 10.10.17
 * Time: 13:40
 */

namespace App\Repositories\Implementation;

use App\Models\NauModels\Offer;
use App\Models\Timeframe;
use App\Repositories\TimeframeRepository;
use App\Services\WeekDaysService;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TimeframeRepositoryEloquent
 * @package App\Repositories\Implementation
 */
class TimeframeRepositoryEloquent extends BaseRepository implements TimeframeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Timeframe::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $timeframes
     * @param Offer $offer
     *
     * @return Collection
     */
    public function createManyForOffer(array $timeframes, Offer $offer): Collection
    {
        return $offer->timeframes()->createMany($this->replaceTimeframesWeekdaysByDays($timeframes));
    }

    /**
     * Replaces each timeframe days value: instead of an array we store in "days" its binary representation.
     *
     * @param array $timeframes
     *
     * @return array
     */
    protected function replaceTimeframesWeekdaysByDays(array $timeframes): array
    {
        foreach ($timeframes as $key => $timeframe) {
            $timeframes[$key]['days'] = app(WeekDaysService::class)->weekDaysToDays($timeframe['days']);
        }

        return $timeframes;
    }

    /**
     * @param array $timeframes
     * @param Offer $offer
     *
     * @return Collection
     */
    public function replaceManyForOffer(array $timeframes, Offer $offer): Collection
    {
        $offer->timeframes()->delete();
        return $this->createManyForOffer($timeframes, $offer);
    }

    /**
     * @param Offer $offer
     * @param int   $days
     *
     * @return Timeframe|null
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByOfferAndDays(Offer $offer, int $days): ?Timeframe
    {
        $this->skipCriteria();

        /**
         * @var Timeframe $model
         */
        $model = $this->model;
        $result = $model->byOffer($offer)->byDays($days)->first();

        $this->resetModel();

        return $this->parserResult($result);
    }
}
