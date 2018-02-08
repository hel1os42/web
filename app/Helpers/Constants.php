<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 25.09.17
 * Time: 15:10
 */

namespace App\Helpers;

/**
 * Interface Constants
 */
interface Constants
{
    /**
     * uuid regular expression
     */
    const UUID_REGEX = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    const DATE_FORMAT = 'Y-m-d H:i:s.uO';

    const TIME_FORMAT = 'H:i:s.uO';

    const SLUG_SEPARATOR = '-';

    const CURRENCIES = [
        self::CURRENCY_UKRAINE,
        self::CURRENCY_PHILIPPINES,
        self::CURRENCY_COLOMBIA,
        self::CURRENCY_EUROPE,
        self::CURRENCY_RUSSIA,
    ];

    const CURRENCY_UKRAINE     = 'UAH';
    const CURRENCY_PHILIPPINES = 'PHP';
    const CURRENCY_COLOMBIA    = 'COP';
    const CURRENCY_EUROPE      = 'EUR';
    const CURRENCY_RUSSIA      = 'RUR';
}
