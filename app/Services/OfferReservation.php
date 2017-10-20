<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 16.10.17
 * Time: 11:20
 */

namespace App\Services;

use App\Models\NauModels\Offer;

/**
 * Interface OfferNauReservation
 * @package App\Services
 */
interface OfferReservation
{
    /**
     * @return bool
     */
    public function isReservable(Offer $offer): bool;

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
