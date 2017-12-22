<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
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

        $feeTransaction                         = new Transaction();
        $feeTransaction->transaction_id         = $faker->uuid;
        $feeTransaction->source_account_id      = $faker->randomDigitNotNull;
        $feeTransaction->destination_account_id = $faker->randomDigitNotNull;
        $feeTransaction->amount                 = $faker->randomFloat();
        $feeTransaction->status                 = 'PAID';
        $feeTransaction->created_at             = Carbon::parse($faker->time())->format('Y-m-d\TH:i:sO');
        $feeTransaction->type                   = 'INCOMING';
        $feeTransaction->feeTransactions        = [];

        $sendNau = [
            'transaction_id'       => $faker->uuid,
            'sourceAccountId'      => $faker->randomDigitNotNull,
            'destinationAccountId' => $faker->randomDigitNotNull,
            'amount'               => $faker->randomFloat(),
            'status'               => 'PAID',
            'createdAt'           => Carbon::parse($faker->time())->format('Y-m-d\TH:i:sO'),
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
            'created_at'             => $sendNau['createdAt'],
            'type'                   => $sendNau['type'],
            'feeTransactions'        => $sendNau['feeTransactions']
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(Transaction::class, function ($response) use ($sendNau, &$eventCalled) {
            $this->assertEquals($sendNau['transaction_id'], $response->getTransactionId(), 'SendNau: transaction_id');
            $this->assertEquals($sendNau['sourceAccountId'], $response->getSourceAccountId(), 'SendNau: source_account_id');
            $this->assertEquals($sendNau['destinationAccountId'], $response->getDestinationAccountId(), 'SendNau: destination_account_id');
            $this->assertEquals($sendNau['amount'], $response->getAmount(), 'SendNau: amount');
            $this->assertEquals($sendNau['status'], $response->getStatus(), 'SendNau: status');
            $this->assertEquals($sendNau['createdAt'], $response->getCreatedAt(), 'SendNau: created_at');
            $this->assertEquals($sendNau['type'], $response->getType(), 'SendNau: type');
            $this->assertEquals($sendNau['feeTransactions'], $response->getFeeTransactions(), 'SendNau: fee_transactions');
            $eventCalled++;
        });

        $exceptionEventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\FailedJob\SendNau::class, function () use(&$exceptionEventCalled) {
            $exceptionEventCalled++;
        });

        $sendNau = $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->sendNau($sendNauMock);

        $sendNau->handle();
        $sendNau->failed((new \Exception));

        $this->assertEquals( 1, $eventCalled, 'Can not listen SendNau response event.');
        $this->assertEquals(1, $exceptionEventCalled, 'Can not listen SendNau failed job.');

        $this->assertEquals([
            'coreService',
            'requestObject',
            'transaction',
        ], $sendNau->__sleep());
    }
}
