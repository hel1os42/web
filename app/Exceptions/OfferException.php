<?php

namespace App\Exceptions;

use App\Models\NauModels\Offer;
use Throwable;

class OfferException extends \Exception
{
    /**
     * @var Offer
     */
    public $offer;

    /**
     * OfferException constructor.
     * @param Offer $offer
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Offer $offer, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->offer = $offer;
        parent::__construct($message, $code, $previous);
    }

    public function getOffer()
    {
        return $this->offer;
    }
}
