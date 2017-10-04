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
}
