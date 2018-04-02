<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 30.03.2018
 * Time: 15:38
 */

namespace App\Services;

/**
 * Interface TimezoneDbService
 * @package App\Services
 */
interface TimezoneDbService
{
    /**
     * @param float|null $latitude
     * @param float|null $longitude
     *
     * @return \DateTimeZone
     */
    public function getTimezoneByLocation(float $latitude = null, float $longitude = null): \DateTimeZone;
}
