<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 03.10.17
 * Time: 14:35
 */

namespace App\Models\Timeframes;

use App\Helpers\WeekDays;

/**
 * Trait HasWeekdays
 * @package App\Models\Timeframes
 */
trait HasWeekdays
{
    /**
     * @return array
     */
    public function getWeekdaysAttribute(): array
    {
        return [];
    }

    /**
     * @param int $weekday
     *
     * @return bool
     */
    public function isWeekDay(int $weekday): bool
    {
        return $this->days & $weekday > 0;
    }

    /**
     * @return bool
     */
    public function isSunday(): bool
    {
        return $this->isWeekDay(WeekDays::SUNDAY);
    }

    /**
     * @return bool
     */
    public function isMonday(): bool
    {
        return $this->isWeekDay(WeekDays::MONDAY);
    }
    /**
     * @return bool
     */
    public function isTuesday(): bool
    {
        return $this->isWeekDay(WeekDays::TUESDAY);
    }
    /**
     * @return bool
     */
    public function isWednesday(): bool
    {
        return $this->isWeekDay(WeekDays::WEDNESDAY);
    }
    /**
     * @return bool
     */
    public function isThursday(): bool
    {
        return $this->isWeekDay(WeekDays::THURSDAY);
    }
    /**
     * @return bool
     */
    public function isFriday(): bool
    {
        return $this->isWeekDay(WeekDays::FRIDAY);
    }
    /**
     * @return bool
     */
    public function isSaturday(): bool
    {
        return $this->isWeekDay(WeekDays::SATURDAY);
    }

}