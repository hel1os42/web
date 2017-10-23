<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 16.10.17
 * Time: 11:20
 */

namespace App\Services;

use App\Models\NauModels\Account;

/**
 * Interface OfferNauReservation
 * @package App\Services
 */
interface OfferReservation
{
    /**
     * @param array   $attributes
     * @param Account $account
     *
     * @return bool
     */
    public function isReservable(array $attributes, Account $account): bool;

    /**
     * @param float $reward
     *
     * @return float
     */
    public function getMinReserved(float $reward): float;

    /**
     * @return int
     */
    public function getReservationMultiplier(): int;
}
