<?php

namespace App\Services;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\User;

/**
 * Interface OffersService
 * NS: App\Services
 */
interface OffersService
{
    public function redeemByOfferAndCode(Offer $offer, string $code): Redemption;

    public function redeemByCode(string $code);
}
