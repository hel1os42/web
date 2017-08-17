<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\Offer\RedemptionException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;

class CannotRedeemException extends RedemptionException
{
    /**
     * CannotRedeemException constructor.
     * @param Offer $offer
     * @param ActivationCode $activationCode
     */
    public function __construct(Offer $offer, ActivationCode $activationCode)
    {
        $message = 'Offer redemption error. Offer id: ' . $offer->getId();

        parent::__construct($offer, $activationCode, $message);
    }
}
