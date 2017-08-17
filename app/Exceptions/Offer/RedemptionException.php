<?php

namespace App\Exceptions\Offer;

use App\Exceptions\OfferException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use Throwable;

class RedemptionException extends OfferException
{
    /**
     * @var ActivationCode|null
     */
    private $activationCode;

    /**
     * RedemptionException constructor.
     * @param Offer $offer
     * @param ActivationCode|null $activationCode
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        Offer $offer,
        ActivationCode $activationCode = null,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        $this->activationCode = $activationCode;
        parent::__construct($offer, $message, $code, $previous);
    }

    /**
     * @return ActivationCode|null
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }
}
