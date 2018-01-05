<?php

namespace App\Services\Auth\Otp;

use App\Exceptions\Exception;
use GuzzleHttp\Client;
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
            $result = $this->client
                ->request($method, $path, $data)
                ->getBody()
                ->getContents();
        } catch (Exception $exception) {
            $this->otpError('Error sending request. Error:' . $exception->getMessage());
        }

        return $result;
    }
}
