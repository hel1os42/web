<?php

namespace Tests\Unit\CoreService\Request;

use App\Models\Account;
use App\Models\Transact;
use OmniSynapse\CoreService\Request\TransactionNotification;
use Tests\TestCase;
use Faker\Factory as Faker;

class TransactionNotificationTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
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
         * Create TransactionNotification request and prepare jsonSerialize for comparing
         */
        $transactionNotificationRequest = new TransactionNotification($transaction, $category);
        $jsonSerialize                  = [
            'txid'     => $txid,
            'category' => $category,
            'from'     => $sendFrom,
            'to'       => $sendTo,
            'amount'   => $amount,
        ];

        /*
         * Compare json strings
         */
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($transactionNotificationRequest->jsonSerialize()), 'jsonSerialize');
    }
}
