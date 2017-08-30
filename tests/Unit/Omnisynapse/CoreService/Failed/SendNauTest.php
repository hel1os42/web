<?php

namespace OmniSynapse\CoreService\Failed;

use Faker\Factory as Faker;
use OmniSynapse\CoreService\FailedJob\SendNau;
use Tests\TestCase;

class SendNauTest extends TestCase
{
    public function testFailedResponse()
    {
        $faker = Faker::create();

        $exception = new \Exception;
        $transaction = [
            'id' => $faker->uuid,
        ];

        $transactionMock = \Mockery::mock(\App\Models\NauModels\Transact::class);
        $transactionMock->shouldReceive('getId')->andReturn($transaction['id']);

        $sendNau = (new SendNau($exception, $transactionMock));

        $this->assertEquals($transaction['id'], $sendNau->getTransaction()->getId());
        $this->assertEquals($exception, $sendNau->getException());
    }
}
