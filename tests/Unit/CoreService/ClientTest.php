<?php

namespace Tests\Unit\CoreService;

use OmniSynapse\CoreService\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testSetClient()
    {
        $coreClient   = new Client();
        $guzzleClient = new \GuzzleHttp\Client();
        $coreClient->setClient($guzzleClient);
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $coreClient->getClient());
    }

    /**
     * @return void
     */
    public function testGetClient()
    {
        $coreClient = new Client();
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $coreClient->getClient());
    }
}
