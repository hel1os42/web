<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OmniSynapse\CoreService\Exception\RequestException;

abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Returns the remaining contents in a string
     *
     * @var object
     */
    protected $responseContent = null;

    /** @var \GuzzleHttp\Client */
    private $client;

    /**
     * AbstractJob constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        /** @var Response $response */
        $response = $this->client->request($this->getHttpMethod(), $this->getHttpPath(),
            [
                'json' => null !== $this->getRequestObject()
                    ? $this->getRequestObject()->jsonSerialize()
                    : null
            ]
        );

        try {
            $this->responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents());
        } catch (\InvalidArgumentException $e) {
            $this->handleError($response, $e->getMessage(), \Illuminate\Http\Response::HTTP_EXPECTATION_FAILED);
        }

        if (floor($response->getStatusCode() * 0.01) > 2) {
            $this->handleError($response);
        }

        $responseClassName = $this->getResponseClass();

        try {
            $jsonMapper = new \JsonMapper();
            $jsonMapper->bExceptionOnMissingData = true;
            $jsonMapper->bExceptionOnUndefinedProperty = true;
            $jsonMapper->bStrictObjectTypeChecking = true;
            $jsonMapper->map($this->responseContent, $responseObject = new $responseClassName);
        } catch (\InvalidArgumentException $e) {
            $this->handleError($response, $e->getMessage(), \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\JsonMapper_Exception $e) {
            $this->handleError($response, $e->getMessage(), \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        logger()->info('Request to the Core', [
            'response' => $this->responseContent
        ]);

        event($responseObject);
    }

    /** @return string */
    abstract protected function getHttpMethod(): string;

    /** @return string */
    abstract protected function getHttpPath(): string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject(): \JsonSerializable;

    /** @return string */
    abstract protected function getResponseClass(): string;

    /**
     * @param Response $response
     * @param string $message
     * @param integer $status
     * @throws RequestException
     */
    protected function handleError(Response $response, $message=null, $status=null)
    {
        if (null === $message) {
            $message = isset($this->responseContent->message)
                ? $this->responseContent->message
                : $response->getReasonPhrase();
        }

        if (null === $status) {
            $status = $response->getStatusCode();
        }

        logger()->error('Error while trying to send request to '.$this->getHttpMethod().' via method '.$this->getHttpMethod(), [
            'statusCode' => $status,
            'message' => $message
        ]);
        logger()->debug('Request and Response', [
            'request' => null !== $this->getRequestObject()
                ? $this->getRequestObject()->jsonSerialize()
                : null,
            'response' => $this->responseContent
        ]);

        throw new RequestException($message, $status);
    }
}
