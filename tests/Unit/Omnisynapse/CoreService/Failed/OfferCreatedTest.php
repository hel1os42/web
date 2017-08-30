<?php

namespace OmniSynapse\CoreService\Failed;

use Faker\Factory as Faker;
use OmniSynapse\CoreService\FailedJob\OfferCreated;
use Tests\TestCase;

class OfferCreatedTest extends TestCase
{
    public function testFailedResponse()
    {
        $faker = Faker::create();

        $exception = new \Exception;
        $offer = [
            'id' => $faker->uuid,
        ];

        $offerMock = \Mockery::mock(\App\Models\NauModels\Offer::class);
        $offerMock->shouldReceive('getId')->andReturn($offer['id']);

        $offerCreated = (new OfferCreated($exception, $offerMock));

        $this->assertEquals($offer['id'], $offerCreated->getOffer()->getId());
        $this->assertEquals($exception, $offerCreated->getException());
    }
}
