<?php

namespace Tests\Unit\CoreService\Request;

use App\Models\Transact;
use Faker\Factory as Faker;
use OmniSynapse\CoreService\Request\SendNau;
use Tests\TestCase;

class SendNauTest extends TestCase
{
    /**
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_ExpectationFailedException
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
        $expectedData  = [
            'source_account_id'      => $sourceAccountId,
            'destination_account_id' => $destinationAccountId,
            'amount'                 => $amount,
        ];

        /*
         * Compare json strings
         */
        $this->assertEquals($expectedData, $sendNauRequest->jsonSerialize(), 'jsonSerialize');
    }
}
