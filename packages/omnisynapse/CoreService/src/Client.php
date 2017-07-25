<?php

namespace OmniSynapse\CoreService;

class Client
{
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT    = 'PUT';
    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';

    /**
     * @var Client
     */
    public $client;

    /**
     * CoreService constructor.
     */
    public function __construct()
    {
        $config = [
            'base_uri'    => config('core-config.base_uri'),
            'verify'      => (bool)config('core-config.verify'),
            'http_errors' => (bool)config('core-config.http_errors'),
        ];

        $this->client = new \GuzzleHttp\Client($config);
    }
}
