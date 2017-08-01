<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Transact;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\FeeTransaction;
use OmniSynapse\CoreService\Response\Transaction;
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

        $feeTransaction                         = new FeeTransaction();
        $feeTransaction->transaction_id         = $faker->uuid;
        $feeTransaction->source_account_id      = $faker->randomDigitNotNull;
        $feeTransaction->destination_account_id = $faker->randomDigitNotNull;
        $feeTransaction->amount                 = $faker->randomFloat();
        $feeTransaction->status                 = 'PAID';
        $feeTransaction->created_at             = Carbon::parse($faker->time())->format('Y-m-d H:i:sO');
        $feeTransaction->type                   = 'INCOMING';
        $feeTransaction->feeTransactions        = [];

        $sendNau = [
            'transaction_id'       => $faker->uuid,
            'sourceAccountId'      => $faker->randomDigitNotNull,
            'destinationAccountId' => $faker->randomDigitNotNull,
            'amount'               => $faker->randomFloat(),
            'status'               => 'PAID',
            'createdAt'           => Carbon::parse($faker->time()),
            'type'                 => 'P2P',
            'feeTransactions'      => [
                $feeTransaction
            ],
        ];

        $sendNauMock = \Mockery::mock(Transact::class);
        $sendNauMock->shouldReceive('getSourceAccountId')->once()->andReturn($sendNau['sourceAccountId']);
        $sendNauMock->shouldReceive('getDestinationAccountId')->once()->andReturn($sendNau['destinationAccountId']);
        $sendNauMock->shouldReceive('getAmount')->once()->andReturn($sendNau['amount']);

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'transaction_id'         => $sendNau['transaction_id'],
            'source_account_id'      => $sendNau['sourceAccountId'],
            'destination_account_id' => $sendNau['destinationAccountId'],
            'amount'                 => $sendNau['amount'],
            'status'                 => $sendNau['status'],
            'created_at'             => $sendNau['createdAt']->format('Y-m-d H:i:sO'),
            'type'                   => $sendNau['type'],
            'feeTransactions'        => $sendNau['feeTransactions']
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(Transaction::class, function ($response) use ($sendNau, &$eventCalled) {
            $this->assertEquals($response->getTransactionId(), $sendNau['transaction_id'], 'SendNau: transaction_id');
            $this->assertEquals($response->getSourceAccountId(), $sendNau['sourceAccountId'], 'SendNau: source_account_id');
            $this->assertEquals($response->getDestinationAccountId(), $sendNau['destinationAccountId'], 'SendNau: destination_account_id');
            $this->assertEquals($response->getAmount(), $sendNau['amount'], 'SendNau: amount');
            $this->assertEquals($response->getStatus(), $sendNau['status'], 'SendNau: status');
            $this->assertEquals($response->getCreatedAt(), $sendNau['createdAt'], 'SendNau: created_at');
            $this->assertEquals($response->getType(), $sendNau['type'], 'SendNau: type');
            $this->assertEquals($response->getFeeTransactions(), $sendNau['feeTransactions'], 'SendNau: fee_transactions');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->sendNau($sendNauMock)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen SendNau response event.');
    }
}
