<?php

namespace App\Services\Auth\Otp;

use App\Jobs\ProcessSendOtpRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
use GuzzleHttp\Exception\RequestException;

class BaseOtpAuth
{
    use OtpAuthTrait;
    /**
     * @var Client
     */
    protected $client;

    /**
     * Number of request retries
     * @var int
     */
    private $tries = 5;

    /**
     * BaseOtpAuth constructor.
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $this->configData = config('otp.gate_data.' . $this->gateName);
        if (!isset($this->client)) {
            $handlerStack = HandlerStack::create();
            $handlerStack->push(Middleware::retry($this->createRetryHandler(), $this->exponentialDelay()), 'retry');
            $this->client = new Client([
                'handler'  => $handlerStack,
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
     * @throws \RuntimeException
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

        $resultContent = $result->getBody()->getContents();

        $result->getBody()->rewind();

        return $resultContent;
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
     * @return \Closure
     */
    private function createRetryHandler()
    {
        return function (
            $retries,
            Psr7Request $request,
            Psr7Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= $this->tries) {
                return false;
            }

            if ($this->isServerError($response) || $this->isConnectError($exception)) {
                $this->otpError(sprintf('Retry to send otp. Uri:%s Retry:%d Last error message:%s',
                    $request->getUri(),
                    $retries,
                    $exception->getMessage()),
                    null, false
                );

                return true;
            }

            return false;
        };

    }

    /**
     * @param Psr7Response $response
     *
     * @return bool
     */
    private function isServerError(Psr7Response $response = null)
    {
        return $response && $response->getStatusCode() >= 500;
    }

    /**
     * @param RequestException $exception
     *
     * @return bool
     */
    private function isConnectError(RequestException $exception = null)
    {
        return $exception instanceof ConnectException;
    }

    /**
     * @return \Closure
     */
    private function exponentialDelay()
    {
        return function ($retryNumber) {
            return (int)pow(2, $retryNumber + 6);
        };
    }
}
