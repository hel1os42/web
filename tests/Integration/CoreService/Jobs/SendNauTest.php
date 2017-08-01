<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Transact;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
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

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "source_account_id" => $sourceAccountId,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Transaction::class, function ($response) use ($sourceAccountId, &$eventCalled) {
            $this->assertEquals($response->getSourceAccountId(), $sourceAccountId, 'SendNau source_account_id is not equals to request source_account_id.');
            $eventCalled++;
        });

        (new CoreServiceImpl([
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => (boolean)env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => (boolean)env('CORE_SERVICE_HTTP_ERRORS', false),
        ]))
            ->setClient($client)
            ->sendNau($sendNau)
            ->handle();

        $this->assertTrue($eventCalled > 0, 'Can not listen Transaction event.');
    }
}
