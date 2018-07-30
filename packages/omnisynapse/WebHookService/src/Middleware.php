<?php

namespace OmniSynapse\WebHookService;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class Middleware
{
    const MAX_RETRIES = 2;

    /**
     * Logging information about request to remote server and response from him to file
     * @param string $service
     * @return Response
     */
    public static function logToFile($service)
    {
        return function (callable $handler) use ($service) {
            return function (Request $request, array $options) use ($handler, $service) {
                return $handler($request, $options)->then(
                    function (Response $response) use ($request, $service) {
                        $messageFormat = '{method} {uri} {req_body} RESPONSE: {code} - {res_body}';
                        $formatter     = new MessageFormatter($messageFormat);
                        $message       = "[$service] " . $formatter->format($request, $response);

                        logger()->debug($message);

                        $response->getBody()->rewind();

                        return $response;
                    }
                );
            };
        };
    }

    /**
     * @return \Closure
     */
    public static function createRetryHandler()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            if (!(self::isServerError($response) || self::isConnectError($exception))) {
                return false;
            }

            logger()->warning(sprintf(
                'Retrying %s %s %s/%s, %s',
                $request->getMethod(),
                $request->getUri(),
                $retries + 1,
                self::MAX_RETRIES,
                $response ? 'status code: ' . $response->getStatusCode() : $exception->getMessage()
            ), [$request->getHeader('Host')[0]]);

            return true;
        };
    }

    /**
     * @param Response $response
     * @return bool
     */
    public static function isServerError(Response $response = null)
    {
        return $response && $response->getStatusCode() >= 500;
    }

    /**
     * @param RequestException $exception
     * @return bool
     */
    public static function isConnectError(RequestException $exception = null)
    {
        return $exception instanceof ConnectException;
    }
}
