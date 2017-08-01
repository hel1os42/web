<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Redemption;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use Tests\TestCase;

class OfferRedemptionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOfferRedemption()
    {
        $faker = Faker::create();

        $offerId = $faker->uuid;
        $userId  = $faker->uuid;

        $redemption = \Mockery::mock(Redemption::class);
        $redemption->shouldReceive('getId')->once()->andReturn($offerId);
        $redemption->shouldReceive('getUserId')->once()->andReturn($userId);

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "user_id" => $userId,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\OfferForRedemption::class, function ($response) use ($userId, &$eventCalled) {
            $this->assertEquals($response->getUserId(), $userId, 'Redemption user_id is not equals to request user_id.');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($client)
            ->offerRedemption($redemption)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen Offer redemption event.');
    }
}
