<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OmniSynapse\CoreService\Exception\RequestException;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var object $requestObject */
    protected $requestObject = null;

    /** @var object $responseContent */
    protected $responseContent = null;

    /**
     * Execute the job.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        /** @var Response $response */
        $response = (new Client())->getClient()->request($this->getHttpMethod(), $this->getHttpPath(),
            [
                'json' => null !== $this->requestObject
                    ? $this->getRequestObject()->jsonSerialize()
                    : null
            ]
        );

        $this->responseContent = \GuzzleHttp\json_decode($response->getBody()->getContents());

        if (floor($response->getStatusCode() * 0.01) > 2) {
            $this->handleError($response);
            return;
        }

        $responseClassName = $this->getResponseClass();

        try {
            (new \JsonMapper)->map($this->responseContent, $responseObject = new $responseClassName);
        } catch (\InvalidArgumentException $e) {
            $this->handleError($response);
            return;
        }

        logger()->info('Request to the Core', [
            'response' => $this->responseContent
        ]);

        event($responseObject);
    }

    /** @return string */
    abstract protected function getHttpMethod() : string;

    /** @return string */
    abstract protected function getHttpPath() : string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject() : \JsonSerializable;

    /** @return string */
    abstract protected function getResponseClass() : string;

    /**
     * @param Response $response
     * @throws RequestException
     */
    protected function handleError(Response $response)
    {
        $errorMessage = isset($this->responseContent->message)
            ? $this->responseContent->message
            : $response->getReasonPhrase();

        logger()->error('Error while trying to send request to '.$this->getHttpMethod().' via method '.$this->getHttpMethod(), [
            'statusCode' => $response->getStatusCode(),
            'message' => $errorMessage
        ]);
        logger()->debug('Request and Response', [
            'request' => $this->requestObject->jsonSerialize(),
            'response' => $this->responseContent
        ]);

        throw new RequestException($errorMessage);
    }
}
