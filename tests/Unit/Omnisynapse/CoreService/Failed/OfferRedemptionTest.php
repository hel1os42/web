<?php

namespace OmniSynapse\CoreService\Failed;

use Faker\Factory as Faker;
use OmniSynapse\CoreService\FailedJob\OfferRedemption;
use Tests\TestCase;

class OfferRedemptionTest extends TestCase
{
    public function testFailedResponse()
    {
        $faker = Faker::create();

        $exception = new \Exception;
        $redemption = [
            'id' => $faker->uuid,
        ];

        $redemptionMock = \Mockery::mock(\App\Models\NauModels\Redemption::class);
        $redemptionMock->shouldReceive('getId')->andReturn($redemption['id']);

        $offerRedemption = (new OfferRedemption($exception, $redemptionMock));

        $this->assertEquals($redemption['id'], $offerRedemption->getRedemption()->getId());
        $this->assertEquals($exception, $offerRedemption->getException());
    }
}
