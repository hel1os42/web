<?php

namespace App\Services\Auth\Otp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BaseOtpAuth
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $gateName;

    /**
     * @var Client
     */
    protected $configData;

    public function __construct()
    {
        $this->configData = config('otp.gate_data.' . $this->gateName);
        $this->client     = new Client([
            'base_uri' => $this->configData['base_api_url']
        ]);
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
     * @param string $phoneNumber
     * @param string $code
     */
    protected function cacheOtpCode(string $phoneNumber, string $code)
    {
        Cache::put($phoneNumber, Hash::make($code), 15);
    }

    /**
     * @param string            $method
     * @param string            $path
     * @param array|string|null $postData
     * @param array|null        $headers
     *
     * @return string
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    protected function request(
        string $method,
        string $path,
        $postData = null,
        array $headers = null,
        $basicAuth = null
    ): string {
        $data = [];
        if ($postData !== null) {
            $key        = is_string($postData) ? 'body' : 'form_params';
            $data[$key] = $postData;
        }

        if ($headers !== null) {
            $data['headers'] = $headers;
        }

        if ($basicAuth !== null) {
            $data['auth'] = [$this->configData['auth_data']['login'], $this->configData['auth_data']['password']];
        }

        try {
            $result = $this->client->request($method, $path, $data);
        } catch (ConnectException $exception) {
            $message = 'Can\'t send otp code. Try again later.';
            logger('OTP: ' . $exception->getMessage() . ' Gate:' . $this->gateName);
            throw new ConnectException($message, $exception->getRequest());
        }

        return $result->getBody()->getContents();
    }

    /**
     * @param string      $loggerMessage
     * @param string|null $exceptionMessage
     *
     * @throws UnprocessableEntityHttpException
     */
    protected function otpError(string $loggerMessage, string $exceptionMessage = null)
    {
        $exceptionMessage = $exceptionMessage ?: 'Can\'t send otp code.';
        logger('OTP: ' . $loggerMessage . ' Gate:' . $this->gateName);
        throw new UnprocessableEntityHttpException($exceptionMessage);
    }

    /**
     * @return string
     */
    protected function createOtp(): string
    {
        return (string)random_int(100000, 999999);
    }

    protected function getOtpMessage($code)
    {
        return 'NAU verification code: ' . $code;
    }
}
