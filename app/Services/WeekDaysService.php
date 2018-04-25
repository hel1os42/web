<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 04.10.17
 * Time: 15:30
 */
namespace App\Services;

use Illuminate\Support\Collection;

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
        self::MONDAY    => 'mo',
        self::TUESDAY   => 'tu',
        self::WEDNESDAY => 'we',
        self::THURSDAY  => 'th',
        self::FRIDAY    => 'fr',
        self::SATURDAY  => 'sa',
        self::SUNDAY    => 'su',
    ];

    const LIST_NUMERIC = [
        self::MONDAY    => 1,
        self::TUESDAY   => 2,
        self::WEDNESDAY => 3,
        self::THURSDAY  => 4,
        self::FRIDAY    => 5,
        self::SATURDAY  => 6,
        self::SUNDAY    => 7,
    ];

    /**
     * @return array
     */
    public function fullList(): array;

    /**
     * @param array $weekDays
     * @param bool  $numeric
     *
     * @return int
     */
    public function weekDaysToDays(array $weekDays, bool $numeric = false): int;

    /**
     * @param int  $days
     * @param bool $numeric
     *
     * @return array
     */
    public function daysToWeekDays(int $days, bool $numeric = false): array;

    /**
     * @param Collection $items
     *
     * @return array
     */
    public function convertOffersCollection(Collection $items): array;

    /**
     * @param Collection $timeframes
     *
     * @return array
     */
    public function convertTimeframesCollection(Collection $timeframes): array;
}
