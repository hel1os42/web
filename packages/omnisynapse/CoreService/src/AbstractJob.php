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

    /** @var Client */
    protected $client;

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

        $responseContent = $response->getBody()->getContents();

        if (floor($response->getStatusCode() * 0.01) > 2) {
            throw new RequestException($this, $response, $responseContent);
        }

        try {
            $decodedContent = \GuzzleHttp\json_decode($responseContent);
        } catch (\InvalidArgumentException $e) {
            throw new RequestException($this, $response, $responseContent, $e);
        }

        $responseClassName = $this->getResponseClass();

        $jsonMapper                                = new \JsonMapper();
        $jsonMapper->bExceptionOnMissingData       = true;
        $jsonMapper->bExceptionOnUndefinedProperty = true;
        $jsonMapper->bStrictObjectTypeChecking     = true;

        try {
            $jsonMapper->map($decodedContent, $responseObject = new $responseClassName);
        } catch (\InvalidArgumentException|\JsonMapper_Exception $e) {
            throw new RequestException($this, $response, $responseContent, $e);
        }

        logger()->debug('Request and Response', [
            'request'  => $this->getRequestObject()->jsonSerialize(),
            'response' => $decodedContent,
        ]);

        event($responseObject);
    }

    /** @return string */
    abstract public function getHttpMethod(): string;

    /** @return string */
    abstract public function getHttpPath(): string;

    /** @return \JsonSerializable */
    abstract public function getRequestObject(): \JsonSerializable;

    /** @return string */
    abstract public function getResponseClass(): string;
}
