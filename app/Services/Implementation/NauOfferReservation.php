<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 16.10.17
 * Time: 11:21
 */

namespace App\Services\Implementation;

use App\Models\NauModels\Account;
use App\Services\OfferReservation;

/**
 * Class OfferNauReservation
 */
class NauOfferReservation implements OfferReservation
{
    /**
     * @param array   $attributes
     * @param Account $account
     *
     * @return bool
     */
    public function isReservable(array $attributes, Account $account): bool
    {
        $reward      = $attributes['reward'];
        $minReserved = $this->getMinReserved($reward);
        $reserved    = $attributes['reserved'];

        return $account->getOwner()->isApproved()
               && 0 !== $reward
               && 0 !== $reserved
               && $reserved >= $minReserved
               && $reserved <= $account->amount;
    }

    /**
     * @param float $reward
     *
     * @return float
     */
    public function getMinReserved(float $reward): float
    {
        return $reward * $this->getReservationMultiplier();
    }

    /**
     * @return int
     */
    public function getReservationMultiplier(): int
    {
        return (int)config('nau.reservation_multiplier');
    }
}
