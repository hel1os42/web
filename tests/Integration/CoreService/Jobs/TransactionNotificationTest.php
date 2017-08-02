<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Transact;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\Transaction;
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
        $feeTransaction                         = new Transaction();
        $feeTransaction->transaction_id         = $faker->uuid;
        $feeTransaction->source_account_id      = $faker->randomDigitNotNull;
        $feeTransaction->destination_account_id = $faker->randomDigitNotNull;
        $feeTransaction->amount                 = $faker->randomFloat();
        $feeTransaction->status                 = 'PAID';
        $feeTransaction->created_at             = Carbon::parse($faker->time())->format('Y-m-d H:i:sO');
        $feeTransaction->type                   = 'INCOMING';
        $feeTransaction->feeTransactions        = [];

        $transaction = [
            'txid'            => $faker->uuid,
            'category'        => $faker->word,
            'sendFrom'        => $faker->randomDigitNotNull,
            'sendTo'          => $faker->randomDigitNotNull,
            'amount'          => $faker->randomFloat(),
            'status'          => $faker->word,
            'createdAt'       => Carbon::parse($faker->time()),
            'type'            => $faker->word,
            'feeTransactions' => [
                $feeTransaction,
            ],
        ];

        /*
         * Prepare Source Account mock
         */
        $sourceMock = $this->createMock(Account::class);
        $sourceMock->method('getId')->willReturn($transaction['sendFrom']);

        /*
         * Prepare Destination Account mock
         */
        $destinationMock = $this->createMock(Account::class);
        $destinationMock->method('getId')->willReturn($transaction['sendTo']);

        /*
         * Prepare Transact mock
         */
        $transactionMock = $this->createMock(Transact::class);
        $transactionMock->method('getId')->willReturn($transaction['txid']);
        $transactionMock->method('getSource')->willReturn($sourceMock);
        $transactionMock->method('getDestination')->willReturn($destinationMock);
        $transactionMock->method('getAmount')->willReturn($transaction['amount']);

        /*
         * Test JOB
         */
        $response = new Response(202, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'transaction_id'         => $transaction['txid'],
            'source_account_id'      => $transaction['sendFrom'],
            'destination_account_id' => $transaction['sendTo'],
            'amount'                 => $transaction['amount'],
            'status'                 => $transaction['status'],
            'created_at'             => $transaction['createdAt']->format('Y-m-d H:i:sO'),
            'type'                   => $transaction['type'],
            'feeTransactions'        => $transaction['feeTransactions']
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(Transaction::class, function ($response) use ($transaction, $feeTransaction, &$eventCalled) {
            $this->assertEquals($transaction['txid'], $response->getTransactionId(), 'TransactionNotification: transaction_id');
            $this->assertEquals($transaction['sendFrom'], $response->getSourceAccountId(), 'TransactionNotification: source_account_id');
            $this->assertEquals($transaction['sendTo'], $response->getDestinationAccountId(), 'TransactionNotification: destination_account_id');
            $this->assertEquals($transaction['amount'], $response->getAmount(), 'TransactionNotification: amount');
            $this->assertEquals($transaction['status'], $response->getStatus(), 'TransactionNotification: status');
            $this->assertEquals($transaction['createdAt'], $response->getCreatedAt(), 'TransactionNotification: created_at');
            $this->assertEquals($transaction['type'], $response->getType(), 'TransactionNotification: type');
            $this->assertEquals($transaction['feeTransactions'], $response->getFeeTransactions(), 'TransactionNotification: fee_transactions');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->transactionNotification($transactionMock, $transaction['category'])
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen TransactionNotification response event.');
    }
}
