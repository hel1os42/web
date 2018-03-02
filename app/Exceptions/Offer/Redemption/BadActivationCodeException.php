<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\Offer\RedemptionException;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;

class BadActivationCodeException extends RedemptionException
{
    /**
     * BadActivationCodeException constructor.
     * @param Offer $offer
     * @param string $activationCode
     */
    public function __construct(?Offer $offer, string $activationCode)
    {
        $message = 'Wrong activation code. Code: ' . $activationCode . ' Offer id: ' . isset($offer) ? $offer->getId() : 'no offer';

        parent::__construct($offer, $activationCode, $message, Response::HTTP_BAD_REQUEST);
    }
}
