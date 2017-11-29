<?php

namespace App\Services;

use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;

/**
 * Interface OffersService
 * NS: App\Services
 */
interface OffersService
{
    public function redeemByOfferAndCode(Offer $offer, string $code): Redemption;

    public function getActivationCodeByCode(string $code): ActivationCode;

    public function redeemByActivationCode(ActivationCode $activationCode);
}
