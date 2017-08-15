<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\BaseException;
use App\Models\NauModels\Offer;

class CannotRedeemException extends BaseException
{
    /**
     * CannotRedeemException constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $message = 'Offer redemption error. Offer id: ' . $offer->getId();

        parent::__construct($message);
    }
}
