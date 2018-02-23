<?php

namespace App\Services\Auth\Otp\SendPulseOtpAuth;

use App\Services\Auth\Otp\BaseOtpAuth;
use App\Services\Auth\Otp\OtpAuth;
use Illuminate\Http\Request as HttpRequest;

/**
 * Class StubOtpAuth
 *
 * This class is just a Stub for phone otp
 *
 * @package App\Helpers
 */
class SendPulseOtpAuth extends BaseOtpAuth implements OtpAuth
{
    /**
     * @var string
     */
    private $token;

    /**
     * SendPulseOtpAuth constructor.
     */
    public function __construct()
    {
        $this->gateName = 'sendpulse';
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
        $this->token = $this->getToken();
        $code        = $this->createOtp();
        $data        = [
            'phones'        => json_encode([$phoneNumber]),
            'body'          => $this->getOtpMessage($code),
            'transliterate' => "0"
        ];

        $header = ['Authorization' => "Bearer " . $this->token];
        $this->createSendOtpRequestJob(
            HttpRequest::METHOD_POST,
            '/sms/send',
            $data,
            $header);

        $this->cacheOtpCode($phoneNumber, $code);
    }

    /**
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException
     */
    private function getToken()
    {
        $authPath = $this->configData['auth_path'];
        $authData = $this->configData['auth_data'];

        if (empty($authData['client_id']) || empty($authData['client_secret'])) {
            $this->otpError('Gate config is not set.');
        }

        $result = $this->request(HttpRequest::METHOD_POST, $authPath, $authData);
        $data   = json_decode($result);

        if (!isset($data->access_token)) {
            $this->otpError('Bad access token');
        }

        return $data->access_token;
    }
}
