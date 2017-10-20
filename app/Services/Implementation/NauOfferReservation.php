<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 16.10.17
 * Time: 11:21
 */

namespace App\Services\Implementation;

use App\Models\Contracts\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Repositories\AccountRepository;
use App\Repositories\OfferRepository;
use App\Services\OfferReservation;
use Illuminate\Http\Request;

/**
 * Class OfferNauReservation
 */
class NauOfferReservation implements OfferReservation
{
    /**
     * @return bool
     */
    public function isReservable(Offer $offer): bool
    {
        $account     = $offer->getAccount();
        $reward      = $offer->getReward();
        $minReserved = $this->getMinReserved($reward);
        $reserved    = $offer->getReserved();

        return 0 !== $reward
               && $account instanceof Account
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
