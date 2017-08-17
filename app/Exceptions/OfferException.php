<?php

namespace App\Exceptions;

use App\Models\NauModels\Offer;
use Throwable;

class OfferException extends \Exception
{
    /**
     * OfferException constructor.
     * @param Offer|null $offer
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(Offer $offer = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
