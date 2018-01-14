<?php

namespace App\Services\Auth\Otp\TwilioOtpAuth;

use App\Services\Auth\Otp\OtpAuth;
use App\Services\Auth\Otp\OtpAuthTrait;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioOtpAuth implements OtpAuth
{
    use OtpAuthTrait;

    /**
     * TwilioOtpAuth constructor.
     */
    public function __construct()
    {
        $this->gateName   = 'twilio';
        $this->configData = config('otp.gate_data.' . $this->gateName);
    }

    /**
     * @param string $phoneNumber
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    public function generateCode(string $phoneNumber): void
    {
        $code     = $this->createOtp();
        $authData = $this->configData['auth_data'];
        try {
            $client = new Client($authData['client_id'], $authData['client_secret']);
            $client->messages->create(
                $phoneNumber,
                [
                    'from' => $this->configData['sender_number'],
                    'body' => $this->getOtpMessage($code),
                ]
            );
        } catch (TwilioException $exception) {
            $this->otpError($exception->getMessage());
        }

        $this->cacheOtpCode($phoneNumber, $code);
    }
}
