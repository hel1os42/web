<?php

namespace App\Services\Auth\Otp\SmsTrafficOtpAuth;

use App\Exceptions\Exception;
use App\Services\Auth\Otp\BaseOtpAuth;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Http\Request;

class SmsTrafficOtpAuth extends BaseOtpAuth implements OtpAuth
{
    /**
     * SmsTrafficOtpAuth constructor.
     */
    public function __construct()
    {
        $this->gateName = 'smstraffic';
        parent::__construct();
    }

    /**
     * @param string $phoneNumber
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    public function generateCode(string $phoneNumber): void
    {
        $code = $this->createOtp();
        $data = [
            'phones'  => $phoneNumber,
            'message' => $this->getOtpMessage($code)
        ];

        $result  = $this->request(
            Request::METHOD_POST,
            $this->configData['main_path'],
            array_merge($this->configData['auth_data'], $data)
        );
        $success = $this->validateResponce($result);

        if (!$success) {
            $this->otpError('Gate result not OK.');
        }

        $this->cacheOtpCode($phoneNumber, $code);
    }

    /**
     * @param null|string $responce
     *
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    private function validateResponce(?string $responce)
    {
        try {
            $xml = new \DOMDocument('1.0', 'utf-8');
            $xml->loadXML($responce);
            $result = $xml->getElementsByTagName('result')->item(0)->nodeValue;
        } catch (Exception $exception) {
            $this->otpError('OTP: Can\'t decode responce.' . $exception->getMessage());
        }

        return $result === 'OK';
    }
}
