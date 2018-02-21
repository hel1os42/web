<?php

namespace App\Services\Auth\Otp;

use App\Jobs\ProcessSendOtpRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BaseOtpAuth
{
    use OtpAuthTrait;
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->configData = config('otp.gate_data.' . $this->gateName);
        if (!isset($this->client)) {
            $this->client = new Client([
                'base_uri' => $this->configData['base_api_url']
            ]);
        }
    }

    /**
     * @param string     $method
     * @param string     $path
     * @param null       $postData
     * @param array|null $headers
     * @param null       $basicAuth
     *
     * @return string
     * @throws ConnectException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function request(
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
     * @param null|string $responce
     *
     * @return bool
     * @SuppressWarnings("unused")
     */
    public function validateResponseString(?string $responce)
    {
        return true;
    }
}
