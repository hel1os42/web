<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Transact;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
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

        $mockHandler = new MockHandler();
        $client      = new Client([
            'handler'       => $mockHandler,
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => env('CORE_SERVICE_HTTP_ERRORS', false),
        ]);
        $transactionNotification = (new CoreServiceImpl($client))
            ->transactionNotification($transaction, $category);
    }
}
