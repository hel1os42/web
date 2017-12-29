<?php

namespace App\Services\Auth\Otp;

interface OtpAuth
{
    /**
     * Generates new code for the phone number
     *
     * @param string $phoneNumber
     *
     * @return void
     */
    public function generateCode(string $phoneNumber): void;

    /**
     * Validates given code for the given phone number
     *
     * @param string $phoneNumber
     * @param string $codeToCheck
     *
     * @return bool
     */
    public function validateCode(string $phoneNumber, string $codeToCheck): bool;
}
