<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 04.10.17
 * Time: 15:32
 */

namespace App\Services\Implementation;

use App\Models\NauModels\Offer;
use App\Models\Timeframe;
use App\Services\WeekDaysService as WeekDaysServiceInterface;
use Illuminate\Support\Collection;

/**
 * Class WeekDaysService
 * @package App\Services\Implementation
 */
class WeekDaysService implements WeekDaysServiceInterface
{
    const LAST_DAY = self::SATURDAY;

    /**
     * @return array
     */
    public function fullList(): array
    {
        return self::LIST;
    }

    /**
     * @param array $weekDays
     * @param bool  $numeric
     *
     * @return int
     */
    public function weekDaysToDays(array $weekDays, bool $numeric = false): int
    {
        $days     = 0;
        $backlist = array_flip($numeric ? self::LIST_NUMERIC : self::LIST);
        foreach ($weekDays as $weekDay) {
            $days = $days | $backlist[$weekDay];
        }

        return $days;
    }

    /**
     * @param int  $days
     * @param bool $numeric
     *
     * @return array
     */
    public function daysToWeekDays(int $days, bool $numeric = false): array
    {
        $weekDays     = [];
        $day          = 1;
        $weekDaysList = $numeric ? self::LIST_NUMERIC : self::LIST;
        while ($day <= self::LAST_DAY) {
            if (($days & $day) > 0) {
                $weekDays[] = $weekDaysList[$day];
            }
            $day = $day << 1;
        }

        return $weekDays;
    }

    /**
     * @param Offer $offer
     * @return array
     */
    public function processOfferTimeFrames(Offer $offer): array
    {
        $separatedTimeFrames = [];
        $timeFrames          = $this->convertTimeframesCollection($offer->timeframes);

        foreach ($timeFrames as $timeFrame) {
            $partialTimeFrames   = $this->splitTimeFrameData($timeFrame);
            $separatedTimeFrames = array_merge($separatedTimeFrames, $partialTimeFrames);
        }

        $orderedDaysList = array_flip(\App\Services\WeekDaysService::LIST);
        $orderedData     = array_replace($orderedDaysList, $separatedTimeFrames);

        $realTimeFrames = array_filter($orderedData, function($timeFrameData) {
            return is_array($timeFrameData);
        });

        return $realTimeFrames;
    }

    /**
     * @param Collection $timeframes
     *
     * @return array
     */
    private function convertTimeframesCollection(Collection $timeframes): array
    {
        return $timeframes->filter(function ($timeframe) { return $timeframe instanceof Timeframe; })
                          ->map(function (Timeframe $timeframe) {
                              return array_merge(
                                  $timeframe->toArray(),
                                  ['days' => $this->daysToWeekDays($timeframe->days)]
                              );
                          })->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    private function splitTimeFrameData(array $data): array
    {
        $partials = [];
        $days     = array_get($data, 'days', []);

        if (empty($days)) {
            return $partials;
        }

        foreach ($days as $day) {
            $timeFrame         = $data;
            $timeFrame['days'] = [$day];

            $partials[$day] = $timeFrame;
        }

        return $partials;
    }
}
