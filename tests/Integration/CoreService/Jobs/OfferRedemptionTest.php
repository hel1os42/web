<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Redemption;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreServiceImpl;
use Tests\TestCase;
use Faker\Factory as Faker;

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
        $redemption->shouldReceive('getId')->andReturn($offerId);
        $redemption->shouldReceive('getUserId')->andReturn($userId);

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "user_id" => $userId,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\OfferForRedemption::class, function ($response) use ($userId, &$eventCalled) {
            $this->assertEquals($response->getUserId(), $userId, 'Redemption user_id is not equals to request user_id.');
            $eventCalled++;
        });

        (new CoreServiceImpl([
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => (boolean)env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => (boolean)env('CORE_SERVICE_HTTP_ERRORS', false),
        ]))
            ->setClient($client)
            ->offerRedemption($redemption)
            ->handle();

        $this->assertTrue($eventCalled > 0, 'Can not listen Offer redemption event.');
    }
}
