<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Transact;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use Tests\TestCase;

class TransactionNotificationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTransactionNotification()
    {
        $faker = Faker::create();

        /*
         * Prepare params
         */
        $txid     = $faker->uuid;
        $category = $faker->word;
        $sendFrom = $faker->uuid;
        $sendTo   = $faker->uuid;
        $amount   = $faker->randomFloat();

        /*
         * Prepare Source Account mock
         */
        $source = $this->createMock(Account::class);
        $source->method('getId')->willReturn($sendFrom);

        /*
         * Prepare Destination Account mock
         */
        $destination = $this->createMock(Account::class);
        $destination->method('getId')->willReturn($sendTo);

        /*
         * Prepare Transact mock
         */
        $transaction = $this->createMock(Transact::class);
        $transaction->method('getId')->willReturn($txid);
        $transaction->method('getSource')->willReturn($source);
        $transaction->method('getDestination')->willReturn($destination);
        $transaction->method('getAmount')->willReturn($amount);

        /*
         * Test JOB
         */
        $response = new Response(202, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "amount" => $amount,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Transaction::class, function ($response) use ($amount, &$eventCalled) {
            $this->assertEquals($response->getAmount(), $amount, 'TransactionNotification amount is not equals to request amount.');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($client)
            ->transactionNotification($transaction, $category)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen Transaction event.');
    }
}
