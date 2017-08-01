<?php

namespace OmniSynapse\CoreService;

class CoreServiceClient
{
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT    = 'PUT';
    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';

    /** @var \GuzzleHttp\Client */
    private $client;

    /** @var array */
    private $config;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client|null $client
     */
    public function __construct(\GuzzleHttp\Client $client=null)
    {
        if (null !== $client) {
            $this->setClient($client);
        }

        $this->config = [
            'base_uri'    => config('core-config.base_uri'),
            'verify'      => (bool)config('core-config.verify'),
            'http_errors' => (bool)config('core-config.http_errors'),
        ];
    }

    /**
     * @param \GuzzleHttp\Client $client
     * @return \GuzzleHttp\Client
     */
    public function setClient(\GuzzleHttp\Client $client): \GuzzleHttp\Client
    {
        return $this->client = $client;
    }

    /**
     * @return \GuzzleHttp\Client|CoreServiceClient
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new \GuzzleHttp\Client($this->config);
        }
        return $this->client;
    }
}
