<?php

namespace OmniSynapse\CoreService\Request;

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
        $expected                       = [
            'txid'     => $txid,
            'category' => $category,
            'from'     => $sendFrom,
            'to'       => $sendTo,
            'amount'   => $amount,
        ];

        /*
         * Compare arrays
         */
        $this->assertEquals($expected, $transactionNotificationRequest->jsonSerialize(), 'Expected array is not equals with transactionNotification array');
    }
}
