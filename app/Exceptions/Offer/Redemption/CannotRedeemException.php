<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\Offer\RedemptionException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;

class CannotRedeemException extends RedemptionException
{
    /**
     * CannotRedeemException constructor.
     * @param Offer $offer
     * @param string $activationCode
     */
    public function __construct(Offer $offer, string $activationCode)
    {
        $message = 'Offer redemption error. Offer id: ' . $offer->getId();

        parent::__construct($offer, $activationCode, $message, Response::HTTP_NOT_ACCEPTABLE);
    }
}
