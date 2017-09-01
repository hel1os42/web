<?php

namespace App\Exceptions;

use App\Models\NauModels\Offer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class OfferException extends HttpException
{
    /**
     * @var Offer
     */
    private $offer;

    /**
     * OfferException constructor.
     * @param Offer $offer
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Offer $offer, $message = "", $code = 500, Throwable $previous = null)
    {
        $this->offer = $offer;
        parent::__construct($code, $message, $previous);
    }

    /**
     * @return Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
