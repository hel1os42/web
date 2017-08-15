<?php

namespace App\Exceptions\Offer\Redemption;

use App\Exceptions\BaseException;
use App\Models\NauModels\Offer;
use Symfony\Component\HttpFoundation\Response;

class BadActivationCodeException extends BaseException
{
    /**
     * BadActivationCodeException constructor.
     * @param Offer $offer
     * @param string $code
     */
    public function __construct(Offer $offer, string $code)
    {
        $message = 'Wrong activation code. Code: ' . $code . ' Offer id: ' . $offer->getId();

        parent::__construct($message);

        $this->statusCode = Response::HTTP_BAD_REQUEST;
    }
}
