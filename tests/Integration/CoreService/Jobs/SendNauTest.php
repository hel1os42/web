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

        $sourceAccountId       = $faker->randomDigitNotNull;
        $destinationAccountId  = $faker->randomDigitNotNull;
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

        (new CoreServiceImpl())
            ->setClient($client)
            ->sendNau($sendNau)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen Transaction event.');
    }
}
