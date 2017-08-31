<?php
namespace OmniSynapse\CoreService\Exception;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Response\Error as ErrorResponse;
use OmniSynapse\CoreService\Response\Error;

class RequestException extends Exception
{
    /** @var AbstractJob $job */
    private $job;

    /** @var Response $response */
    private $response;

    /** @var string $rawResponse */
    private $rawResponse;

    /** @var Error|null $errorResponse */
    private $errorResponse=null;

    /**
     * RequestException constructor.
     * @param AbstractJob $job
     * @param Response $response
     * @param string $rawResponse
     * @param \Throwable|null $previous
     */
    public function __construct(AbstractJob $job, Response $response, string $rawResponse = null, \Throwable $previous = null)
    {
        $this->job         = $job;
        $this->response    = $response;
        $this->rawResponse = $rawResponse;

        $jsonMapper                                = new \JsonMapper();
        $jsonMapper->bExceptionOnMissingData       = true;
        $jsonMapper->bExceptionOnUndefinedProperty = true;
        $jsonMapper->bStrictObjectTypeChecking     = true;

        try {
            $jsonMapper->map(\GuzzleHttp\json_decode($rawResponse), $contents = new ErrorResponse());
        } catch (\InvalidArgumentException $e) {
            $contents = null;
        } catch (\JsonMapper_Exception $e) {
            $contents = null;
        }

        $this->errorResponse = $contents;

        $message = null !== $contents && isset($contents->message)
            ? $contents->message
            : $response->getReasonPhrase();

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
}
