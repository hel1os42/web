<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 04.10.17
 * Time: 15:32
 */

namespace App\Services\Implementation;

use App\Services\WeekDaysService as WeekDaysServiceInterface;

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
     *
     * @return int
     */
    public function weekDaysToDays(array $weekDays): int
    {
        $days     = 0;
        $backlist = array_flip(self::LIST);
        foreach ($weekDays as $weekDay) {
            $days = $days | $backlist[$weekDay];
        }

        return $days;
    }

    /**
     * @param int $weekDays
     *
     * @return array
     */
    public function daysToWeekDays(int $days): array
    {
        $weekDays = [];
        $day      = 1;
        while ($day <= self::LAST_DAY) {
            if (($days & $day) > 0) {
                $weekDays[$day] = self::LIST[$day];
            }
            $day = $day << 1;
        }

        return $weekDays;
    }
}
