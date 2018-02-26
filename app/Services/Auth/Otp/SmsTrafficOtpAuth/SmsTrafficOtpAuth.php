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
        if($this->specialNumberCheck($phoneNumber)) {
            return;
        }
        $code = $this->createOtp();
        $data = [
            'phones'  => $phoneNumber,
            'message' => $this->getOtpMessage($code)
        ];

        $this->createSendOtpRequestJob(
            Request::METHOD_POST,
            $this->configData['main_path'],
            array_merge($this->configData['auth_data'], $data)
        );

        $this->cacheOtpCode($phoneNumber, $code);
    }
}
