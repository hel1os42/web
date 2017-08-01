<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Transact;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use Tests\TestCase;

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

        $sourceAccountId       = $faker->randomDigitNotNull;
        $destinationAccountId  = $faker->randomDigitNotNull;
        $amount                = $faker->randomFloat();

        $sendNau               = \Mockery::mock(Transact::class);
        $sendNau->shouldReceive('getSourceAccountId')->once()->andReturn($sourceAccountId);
        $sendNau->shouldReceive('getDestinationAccountId')->once()->andReturn($destinationAccountId);
        $sendNau->shouldReceive('getAmount')->once()->andReturn($amount);

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "source_account_id" => $sourceAccountId,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Transaction::class, function ($response) use ($sourceAccountId, &$eventCalled) {
            $this->assertEquals($response->getSourceAccountId(), $sourceAccountId, 'SendNau source_account_id is not equals to request source_account_id.');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($client)
            ->sendNau($sendNau)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen Transaction event.');
    }
}
