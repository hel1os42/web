<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Transact;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use OmniSynapse\CoreService\CoreServiceImpl;
use Tests\TestCase;
use Faker\Factory as Faker;

class SendNauTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendNau()
    {
        $faker = Faker::create();

        $sourceAccountId       = $faker->uuid;
        $destinationAccountId  = $faker->uuid;
        $amount                = $faker->randomFloat();

        $sendNau               = \Mockery::mock(Transact::class);
        $sendNau->shouldReceive('getSourceAccountId')->andReturn($sourceAccountId);
        $sendNau->shouldReceive('getDestinationAccountId')->andReturn($destinationAccountId);
        $sendNau->shouldReceive('getAmount')->andReturn($amount);

        $mockHandler = new MockHandler();
        $client      = new Client([
            'handler'       => $mockHandler,
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => env('CORE_SERVICE_HTTP_ERRORS', false),
        ]);
        $sendNauImpl = (new CoreServiceImpl($client))
            ->sendNau($sendNau);
    }
}
