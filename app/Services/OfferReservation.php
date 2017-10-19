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
     * @param Offer $offer
     *
     * @return OfferReservation
     */
    public function setOffer(Offer $offer): OfferReservation;
    /**
     * @return bool
     */
    public function isReservable(): bool;
}
