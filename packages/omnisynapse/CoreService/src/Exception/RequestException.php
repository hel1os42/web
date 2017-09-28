<?php

namespace OmniSynapse\CoreService\Exception;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Response\Error;
use OmniSynapse\CoreService\Response\Error as ErrorResponse;

class RequestException extends Exception
{
    const CORE_EXCEPTION_EXPLANATION = [
        'com.toavalon.nau.core.entities.Wallet$NotEnoughFunds' => [
            'Not enough funds'
        ]
    ];

    /** @var AbstractJob $job */
    private $job;

    /** @var Response $response */
    private $response;

    /** @var string $rawResponse */
    private $rawResponse;

    /** @var Error|null $errorResponse */
    private $errorResponse = null;

    /**
     * RequestException constructor.
     *
     * @param AbstractJob     $job
     * @param Response        $response
     * @param string          $rawResponse
     * @param \Throwable|null $previous
     */
    public function __construct(
        AbstractJob $job,
        Response $response,
        string $rawResponse = null,
        \Throwable $previous = null
    ) {
        $this->job         = $job;
        $this->response    = $response;
        $this->rawResponse = $rawResponse;

        $message = $this->parseMessageFromResponse($response, $rawResponse);

        parent::__construct($message, $response->getStatusCode(), $previous);
    }

    /**
     * @return AbstractJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @return Error|null
     */
    public function getErrorResponse()
    {
        return $this->errorResponse;
    }

    /**
     * @param string $rawResponse
     *
     * @return null|ErrorResponse
     */
    private function mapResponse(string $rawResponse)
    {
        $jsonMapper                                = new \JsonMapper();
        $jsonMapper->bExceptionOnMissingData       = true;
        $jsonMapper->bExceptionOnUndefinedProperty = true;
        $jsonMapper->bStrictObjectTypeChecking     = true;

        try {
            $json = \GuzzleHttp\json_decode($rawResponse);

            $jsonMapper->map($json, $contents = new ErrorResponse());
        } catch (\InvalidArgumentException $e) {
            $contents = null;
        } catch (\JsonMapper_Exception $e) {
            $contents = null;
        }

        return $contents;
    }

    /**
     * @param Response $response
     * @param string   $rawResponse
     *
     * @return string
     */
    private function parseMessageFromResponse(Response $response, string $rawResponse): string
    {
        $contents            = $this->mapResponse($rawResponse);
        $this->errorResponse = $contents;
        $message             = $response->getReasonPhrase();

        if (null === $contents) {
            return $message;
        }

        if (isset($contents->message)) {
            $message = $contents->message;
        } elseif (in_array($contents->exception, self::CORE_EXCEPTION_EXPLANATION)) {
            $message = self::CORE_EXCEPTION_EXPLANATION[$contents->exception];
        }

        return $message;
    }
}
