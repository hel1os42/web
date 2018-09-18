<?php

namespace App\Services\Auth\Otp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;
use GuzzleHttp\Exception\RequestException;

trait OtpHttpTrait
{

    /**
     * Number of request retries
     * @var int
     */
    private $tries = 5;

    /**
     * @param string $baseUri
     *
     * @return Client
     * @throws \RuntimeException
     */
    protected function createClient(string $baseUri)
    {
        return new Client([
            'handler'  => $this->generateHandleStack(),
            'base_uri' => $baseUri
        ]);
    }

    /**
     * @return HandlerStack
     * @throws \RuntimeException
     */
    protected function generateHandleStack()
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(Middleware::retry($this->createRetryHandler(), $this->exponentialDelay()), 'retry');

        return $handlerStack;
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
            /** @var Psr7Response $result */
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
                $errorMessage = $exception instanceof \Exception
                    ? $exception->getMessage()
                    : '';

                $responseContent = $response instanceof Psr7Response
                    ? $response->getBody()->getContents()
                    : '';

                $loggerMessage = sprintf('Retry to send otp. Uri:%s Retry:%d Last error message:%s. Last Response: %s',
                    $request->getUri(),
                    $retries,
                    $errorMessage,
                    $responseContent
                );

                $this->otpError($loggerMessage,null, false);

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
