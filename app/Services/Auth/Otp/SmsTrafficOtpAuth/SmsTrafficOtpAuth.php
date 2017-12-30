<?php

namespace App\Services\Auth\Otp\SmsTrafficOtpAuth;

use App\Exceptions\Exception;
use App\Services\Auth\Otp\OtpAuth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SmsTrafficOtpAuth implements OtpAuth
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $configData;

    /**
     * SmsTrafficOtpAuth constructor.
     */
    public function __construct()
    {
        $this->configData = config('otp.gate_data.smstraffic');
        $this->client     = new Client([
            'base_uri' => $this->configData['base_api_url']
        ]);
    }

    /**
     * @param string $phoneNumber
     *
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function generateCode(string $phoneNumber): void
    {
        $code = random_int(100000, 999999);
        $data = [
            'phones'  => $phoneNumber,
            'message' => 'NAU verification code: ' . $code
        ];

        $result = $this->validateResponce($this->mainPostRequest($data));

        if(!$result) {
            logger('OTP: Gate result not OK.');
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        Cache::put($phoneNumber, Hash::make($code), 15);
    }

    /**
     * @param string $phoneNumber
     * @param string $codeToCheck
     *
     * @return bool
     */
    public function validateCode(string $phoneNumber, string $codeToCheck): bool
    {
        return Cache::has($phoneNumber)
            ? Hash::check($codeToCheck, Cache::get($phoneNumber))
            : false;
    }

    /**
     * @param null|string $responce
     *
     * @return bool
     * @throws UnprocessableEntityHttpException
     */
    private function validateResponce(?string $responce)
    {
        try {
            $xml    = new \DOMDocument('1.0', 'utf-8');
            $xml->loadXML($responce);
            $result = $xml->getElementsByTagName('result')->item(0)->nodeValue;
        } catch (Exception $exception) {
            logger('OTP: Can\'t decode responce.' . $exception->getMessage());
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        return $result === 'OK';
    }

    /**
     * @param array $data
     *
     * @return string
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    private function mainPostRequest(array $data)
    {
        try {
            $result = $this->client
                ->request('POST', $this->configData['main_path'],
                    ['form_params' => array_merge($this->configData['auth_data'], $data)])
                ->getBody();
        } catch (Exception $exception) {
            logger('Can\'t send otp code.' . $exception->getMessage());
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        return $result->getContents();
    }


}