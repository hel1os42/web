<?php

namespace App\Services\Auth\Otp\TwilioOtpAuth;

use App\Services\Auth\Otp\BaseOtpAuth;
use App\Services\Auth\Otp\OtpAuth;
use App\Services\Auth\Otp\OtpAuthTrait;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioOtpAuth extends BaseOtpAuth implements OtpAuth
{
    use OtpAuthTrait;

    /**
     * TwilioOtpAuth constructor.
     */
    public function __construct()
    {
        $this->gateName = 'twilio';
        parent::__construct();
    }

    /**
     * @param string $phoneNumber
     */
    public function codeGenerate(string $phoneNumber): void
    {
        $code         = $this->createOtp();
        $data         = $this->configData['auth_data'];
        $data['body'] = $this->getOtpMessage($code);

        $this->createSendOtpRequestJob( '', '', $data);

        $this->cacheOtpCode($phoneNumber, $code);
    }

    /**
     * @param string     $method
     * @param string     $path
     * @param null       $postData
     * @param array|null $headers
     * @param null       $basicAuth
     *
     * @return string
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    public function request(
        string $method,
        string $path,
        $postData = null,
        array $headers = null,
        $basicAuth = null
    ): string {
        try {
            $client = new Client($postData['client_id'], $postData['client_secret']);
            $client->messages->create(
                $postData['phoneNumber'],
                [
                    'from' => $this->configData['sender_number'],
                    'body' => $postData['body'],
                ]
            );
        } catch (TwilioException $exception) {
            $this->otpError($exception->getMessage());
        }
        return '';
    }
}
