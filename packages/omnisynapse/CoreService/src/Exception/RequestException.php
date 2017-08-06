<?php
namespace OmniSynapse\CoreService\Exception;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\AbstractJob;

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
     * @param \Throwable|null $previous
     * @param \stdClass|null $responseContent
     */
    public function __construct(AbstractJob $job, Response $response, \Throwable $previous = null, \stdClass $responseContent = null)
    {
        $this->job      = $job;
        $this->response = $response;

        $contents       = null !== $responseContent
            ? $responseContent
            : $response->getBody()->getContents();
        $status         = 0 === $response->getStatusCode() || \Illuminate\Http\Response::HTTP_OK === $response->getStatusCode()
            ? $this->status
            : $response->getStatusCode();

        if (null === $responseContent) {
            try {
                $contents = \GuzzleHttp\json_decode($contents);
            } catch (\InvalidArgumentException $e) {
                $contents = null;
            }
        }

        $message = null !== $contents && isset($contents->message)
            ? $contents->message
            : $response->getReasonPhrase();

        logger()->error('Error while trying to send request to '.config('core.base_uri').$job->getHttpPath().' via method '.$job->getHttpMethod(), [
            'statusCode' => $status,
            'message' => $message
        ]);
        logger()->debug('Request and Response', [
            'request' => null !== $job->getRequestObject()
                ? $job->getRequestObject()->jsonSerialize()
                : null,
            'response' => $contents
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
