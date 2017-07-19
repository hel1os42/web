<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;

class Client
{
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT    = 'PUT';
    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * CoreService constructor.
     */
    public function __construct()
    {
        $config = [
            'base_uri'    => $this->getBaseUrl(),
            'verify'      => config('core-config.verify'),
            'http_errors' => config('core-config.http_errors'),
        ];

        $this->client = new \GuzzleHttp\Client($config);
    }

    /**
     * @return string
     */
    private function getBaseUrl() : string
    {
        return config('core-config.base_uri');
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient() : \GuzzleHttp\Client
    {
        return $this->client;
    }

    /**
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }

    /**
     * @return object
     */
    public function getContent() : object
    {
        return json_decode($this->response->getBody()->getContents());
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->getResponse()->getStatusCode();
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $params
     * @return Client
     * @throws
     */
    public function request($method, $path, $params=[]) : Client
    {
        /** @var \GuzzleHttp\Client $client */
        $client = $this->getClient();

        try {
            $this->response = $client->request($method, $path, [
                'json' => $params,
            ]);
        } catch (BadResponseException $e) {
            // TODO: finish error handler
        }

        return $this;
    }
}