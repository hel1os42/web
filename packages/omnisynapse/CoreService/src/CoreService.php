<?php

namespace OmniSynapse\CoreService;

use GuzzleHttp\Psr7\Response;

class CoreService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $baseUrl = '';

    /**
     * CoreService constructor.
     */
    public function __construct()
    {
        $verify = true;

        if (app()->environment() === 'dev') {
            $verify = false;
        }

        $config = [
            'base_uri'    => $this->baseUrl,
            'verify'      => $verify,
            'http_errors' => false,
        ];

        $this->client = new Client($config);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return json_decode($this->response->getBody()->getContents());
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}