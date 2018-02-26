<?php

namespace App\Services\Auth\Otp\SmsFlyOtpAuth;

use App\Services\Auth\Otp\BaseOtpAuth;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Http\Request;

class SmsFlyOtpAuth extends BaseOtpAuth implements OtpAuth
{
    /**
     * SmsTrafficOtpAuth constructor.
     */
    public function __construct()
    {
        $this->gateName = 'smsfly';
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

        $data  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $data .= "<request>";
        $data .= "<operation>SENDSMS</operation>";
        $data .= '		<message start_time="AUTO" end_time="AUTO" lifetime="4" rate="1" desc="OTP API">';
        $data .= "		<body>" . $this->getOtpMessage($code) . "</body>";
        $data .= "		<recipient>" . $phoneNumber . "</recipient>";
        $data .= "</message>";
        $data .= "</request>";

        $header = ['Content-Type: text/xml' => "Accept: text/xml"];

        $auth = [
            'auth' => [$this->configData['auth_data']['login'], $this->configData['auth_data']['password']]
        ];

        $this->createSendOtpRequestJob(
            Request::METHOD_POST,
            $this->configData['main_path'],
            $data,
            $header,
            $auth

        );

        $this->cacheOtpCode($phoneNumber, $code);
    }
}
