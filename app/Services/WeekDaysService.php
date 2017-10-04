<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 04.10.17
 * Time: 15:30
 */
namespace App\Services;

/**
 * Interface WeekDaysService
 * @package App\Services
 */
interface WeekDaysService
{
    const SUNDAY = 1 << 0;

    const MONDAY = 1 << 1;

    const TUESDAY = 1 << 2;

    const WEDNESDAY = 1 << 3;

    const THURSDAY = 1 << 4;

    const FRIDAY = 1 << 5;

    const SATURDAY = 1 << 6;

    const LIST = [
        self::SUNDAY    => 'su',
        self::MONDAY    => 'mo',
        self::TUESDAY   => 'tu',
        self::WEDNESDAY => 'we',
        self::THURSDAY  => 'th',
        self::FRIDAY    => 'fr',
        self::SATURDAY  => 'sa',
    ];

    /**
     * @return array
     */
    public function fullList(): array;

    /**
     * @param array $weekDays
     *
     * @return int
     */
    public function weekDaysToDays(array $weekDays): int;

    /**
     * @param int $weekDays
     *
     * @return array
     */
    public function daysToWeekDays(int $days): array;
}
