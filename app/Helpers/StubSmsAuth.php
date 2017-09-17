<?php

namespace App\Helpers;

class StubSmsAuth implements SmsAuth
{
    /**
     * @param string $phoneNumber
     * @return string
     */
    public function getCode(string $phoneNumber): string
    {
        return substr($phoneNumber, -6);
    }
}
