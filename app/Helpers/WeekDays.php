<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 03.10.17
 * Time: 14:23
 */

namespace App\Helpers;

/**
 * Interface WeekDays
 */
interface WeekDays
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
}
