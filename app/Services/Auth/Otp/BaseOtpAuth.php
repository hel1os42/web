<?php

namespace App\Services\Auth\Otp;

use App\Jobs\ProcessSendOtpRequest;
use App\Services\Auth\Otp\Exceptions\UnsupportedCountryException;
use GuzzleHttp\Client;

/**
 * Class BaseOtpAuth
 * @package App\Services\Auth\Otp
 *
 * @property Client $client
 */
abstract class BaseOtpAuth
{
    use OtpAuthTrait, OtpHttpTrait;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    public $blacklistCodes = [
        '+44', // United Kingdom
    ];

    /**
     * BaseOtpAuth constructor.
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->configData = config('otp.gate_data.' . $this->gateName);
        if (!isset($this->client)) {
            $this->client = $this->createClient($this->configData['base_api_url']);
        }
    }

    /**
     * @param string $phoneNumber
     */
    final public function generateCode(string $phoneNumber): void
    {
        if ($this->specialNumberCheck($phoneNumber)) {
            return;
        }

        $this->validatePhoneNumber($phoneNumber);

        $this->codeGenerate($phoneNumber);
    }

    /**
     * @param string $phoneNumber
     */
    abstract protected function codeGenerate(string $phoneNumber): void;


    protected function createSendOtpRequestJob(
        string $method,
        string $path,
        $postData = null,
        array $headers = null,
        $basicAuth = null
    ) {
        ProcessSendOtpRequest::dispatch(config('otp.gate_class.' . $this->gateName), [
            $method,
            $path,
            $postData,
            $headers,
            $basicAuth
        ]);
    }

    /**
     * @param string $phoneNumber
     *
     * @return bool
     */
    protected function specialNumberCheck(string $phoneNumber): bool
    {
        if ($phoneNumber === config('otp.special_number')) {
            $this->cacheOtpCode($phoneNumber, substr($phoneNumber, -4));

            return true;
        }

        return false;
    }

    /**
     * @param string $phoneNumber
     */
    protected function validatePhoneNumber(string $phoneNumber)
    {
        foreach ($this->blacklistCodes as $code) {
            if (starts_with($phoneNumber, $code)) {
                throw new UnsupportedCountryException(trans('errors.sms.unsupported_number'));
            }
        }
    }
}
