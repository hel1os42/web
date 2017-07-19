<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Client */
    private $client;

    /** @var object */
    protected $requestObject = null;

    /**
     * Job constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \JsonMapper_Exception
     */
    public function handle()
    {
        $response = $this->client->request(
            $this->getHttpMethod(),
            $this->getHttpPath(),
            [
                'json' => null !== $this->requestObject
                    ? $this->getRequestObject()->jsonSerialize()
                    : null
            ]
        )->getResponse();

        if ($response->getStatusCode() % 200 > 100) { // TODO: What if status is 404 ? Result will be 4, and <= 100 (no error).
            $this->handleError($response);
            return;
        }

        $responseClassName = $this->getResponseClass();

        try {
            (new \JsonMapper)->map($this->client->getContent(), $responseObject = new $responseClassName);
        } catch (\JsonMapper_Exception $e) {
            $this->handleError($response);
            return;
        }

        event($responseObject);
    }

    /** @return string */
    abstract protected function getHttpMethod() : string;

    /** @reutnr string */
    abstract protected function getHttpPath() : string;

    /** @return \JsonSerializable */
    abstract protected function getRequestObject() : \JsonSerializable;

    /** @return string */
    abstract protected function getResponseClass() : string;

    abstract protected function handleError(Response $response);
}