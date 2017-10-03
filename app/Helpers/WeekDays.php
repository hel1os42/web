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
    const SUNDAY = 1;

    const MONDAY = 2;

    const TUESDAY = 4;

    const WEDNESDAY = 8;

    const THURSDAY = 16;

    const FRIDAY = 32;

    const SATURDAY = 64;

    const VERBAL = [
        self::SUNDAY    => 'su',
        self::MONDAY    => 'mo',
        self::TUESDAY   => 'tu',
        self::WEDNESDAY => 'we',
        self::THURSDAY  => 'th',
        self::FRIDAY    => 'fr',
        self::SATURDAY  => 'sa',
    ];
}
