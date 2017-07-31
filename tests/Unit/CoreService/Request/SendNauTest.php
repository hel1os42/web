<?php

namespace Tests\Unit\CoreService\Request;

use App\Models\Transact;
use OmniSynapse\CoreService\Request\SendNau;
use Tests\TestCase;
use Faker\Factory as Faker;

class SendNauTest extends TestCase
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
        $sourceAccountId      = $faker->randomDigitNotNull;
        $destinationAccountId = $faker->randomDigitNotNull;
        $amount               = $faker->randomFloat();

        /*
         * Prepare Transact mock
         */
        $transaction = $this->createMock(Transact::class);
        $transaction->method('getSourceAccountId')->willReturn($sourceAccountId);
        $transaction->method('getDestinationAccountId')->willReturn($destinationAccountId);
        $transaction->method('getAmount')->willReturn($amount);

        /*
         * Create SendNau request and prepare jsonSerialize for comparing
         */
        $sendNauRequest = new SendNau($transaction);
        $jsonSerialize  = [
            'source_account_id'      => $sourceAccountId,
            'destination_account_id' => $destinationAccountId,
            'amount'                 => $amount,
        ];

        /*
         * Compare json strings
         */
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($sendNauRequest->jsonSerialize()), 'jsonSerialize');
    }
}
