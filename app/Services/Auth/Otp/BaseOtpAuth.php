<?php

namespace App\Services\Auth\Otp;

use App\Jobs\ProcessSendOtpRequest;
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
}
