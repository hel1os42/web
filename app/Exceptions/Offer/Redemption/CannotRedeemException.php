<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\Offer\RedemptionException;
use App\Models\NauModels\Offer;

class CannotRedeemException extends RedemptionException
{
    /**
     * CannotRedeemException constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $message = 'Offer redemption error. Offer id: ' . $offer->getId();

        parent::__construct($offer, $message);
    }
}
