<?php

namespace App\Services\Auth\Otp\Exceptions;

use Throwable;

/**
 * Class UnsupportedCountryOtpException
 * @package App\Services\Auth\Otp\Exceptions
 */
class UnsupportedCountryException extends OtpException
{

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
