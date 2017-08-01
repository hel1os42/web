<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Transact;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreServiceImpl;
use Tests\TestCase;
use Faker\Factory as Faker;

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
            "source_account_id" => $sendFrom,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Transaction::class, function ($response) use ($sendFrom, &$eventCalled) {
            $this->assertEquals($response->getSourceAccountId(), $sendFrom, 'TransactionNotification source_account_id is not equals to request source_account_id.');
            $eventCalled++;
        });

        (new CoreServiceImpl([
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => (boolean)env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => (boolean)env('CORE_SERVICE_HTTP_ERRORS', false),
        ]))
            ->setClient($client)
            ->transactionNotification($transaction, $category)
            ->handle();

        $this->assertTrue($eventCalled > 0, 'Can not listen Transaction event.');
    }
}
