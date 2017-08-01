<?php

namespace Tests\Unit\CoreService;

use OmniSynapse\CoreService\CoreServiceClient;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * @return void
     */
    public function testSetClient()
    {
        $coreClient   = new CoreServiceClient();
        $guzzleClient = new \GuzzleHttp\Client();
        $coreClient->setClient($guzzleClient);
        $this->assertEquals($guzzleClient, $coreClient->getClient());
    }

    /**
     * @return void
     */
    public function testGetClient()
    {
        $coreClient = new CoreServiceClient();
        $this->assertInstanceOf(\GuzzleHttp\Client::class, $coreClient->getClient());
    }
}
