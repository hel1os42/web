<?php

namespace App\Services;

use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;

/**
 * Interface OffersService
 * NS: App\Services
 */
interface OffersService
{
    public function redeem(Offer $offer, string $code): Redemption;
}
