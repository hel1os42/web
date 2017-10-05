<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenException extends HttpException
{

    /**
     * TokenException constructor.
     * @param string $currency
     */
    public function __construct(string $currency)
    {
        $message = 'You do not have ' . $currency . ' account.';

        parent::__construct(Response::HTTP_FORBIDDEN, $message);
    }
}
