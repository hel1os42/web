<?php

namespace App\Helpers;

interface SmsAuth
{
    /**
     * @param string $phoneNumber
     * @return string
     */
    public function getCode(string $phoneNumber): string;
}
