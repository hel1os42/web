<?php

namespace App\Exceptions\Offer;

use App\Exceptions\OfferException;
use App\Models\ActivationCode;
use App\Models\NauModels\Offer;
use Throwable;

class RedemptionException extends OfferException
{
    /**
     * @var string
     */
    private $activationCode;

    /**
     * RedemptionException constructor.
     * @param Offer $offer
     * @param string $activationCode
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        Offer $offer,
        string $activationCode,
        $message = "",
        $code = 500,
        Throwable $previous = null
    ) {
        $this->activationCode = $activationCode;
        parent::__construct($offer, $message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getActivationCode(): string
    {
        return $this->activationCode;
    }
}
