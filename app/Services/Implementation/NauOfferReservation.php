<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 16.10.17
 * Time: 11:21
 */

namespace App\Services\Implementation;

use App\Models\Contracts\Currency;
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
    protected $request;
    protected $offer;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Offer $offer
     *
     * @return $this
     */
    public function setOffer(Offer $offer): OfferReservation
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReservable(): bool
    {
        $reward = 0;
        if ($this->offer instanceof Offer) {
            $reward = $this->offer->reward;
        } elseif ($this->request->has('reward')) {
            $reward = (float)$this->request->get('reward');
        }

        $account = auth()->user()->getAccountFor(Currency::NAU);

        return 0 !== $reward && $reward * self::getReservationMultiplier() <= $account->amount;
    }

    /**
     * @return int
     */
    public static function getReservationMultiplier(): int
    {
        return (int)config('nau.reservation_multiplier');
    }
}
