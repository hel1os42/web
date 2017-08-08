<?php
namespace OmniSynapse\CoreService\Exception;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Response\Error;

class RequestException extends Exception
{
    /** @var int $status */
    private $status = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @var AbstractJob $job */
    private $job;

    /** @var Response $response */
    private $response;

    /**
     * RequestException constructor.
     * @param AbstractJob $job
     * @param Response $response
     * @param string $responseContent
     * @param \Throwable|null $previous
     */
    public function __construct(AbstractJob $job, Response $response, string $responseContent = null, \Throwable $previous = null)
    {
        $this->job      = $job;
        $this->response = $response;

        $status         = 0 === $response->getStatusCode() || \Illuminate\Http\Response::HTTP_OK === $response->getStatusCode()
            ? $this->status
            : $response->getStatusCode();

        try {
            $contents = \GuzzleHttp\json_decode($responseContent);
        } catch (\InvalidArgumentException $e) {
            $contents = null;
        }

        $jsonMapper                                = new \JsonMapper();
        $jsonMapper->bExceptionOnMissingData       = true;
        $jsonMapper->bExceptionOnUndefinedProperty = true;
        $jsonMapper->bStrictObjectTypeChecking     = true;

        if (null !== $contents) {
            try {
                $jsonMapper->map($contents, $contents = new Error());
            } catch (\InvalidArgumentException|\JsonMapper_Exception $e) {
                $contents = null;
            }
        }

        $message = null !== $contents && isset($contents->message)
            ? $contents->message
            : $response->getReasonPhrase();

        logger()->error('Error while trying to send request to '.config('core.base_uri').$job->getHttpPath().' via method '.$job->getHttpMethod(), [
            'statusCode' => $status,
            'message'    => $message
        ]);
        logger()->debug('Request and Response', [
            'request'  => null !== $job->getRequestObject()
                ? $job->getRequestObject()->jsonSerialize()
                : null,
            'response' => true === $contents instanceof Error
                ? $contents->jsonSerialize()
                : $contents,
        ]);

        parent::__construct($message, $status, $previous);
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
}
