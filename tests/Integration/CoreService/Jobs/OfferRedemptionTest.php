<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Redemption;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
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

//        $mockHandler = new MockHandler();
//        $client      = new Client([
//            'handler'       => $mockHandler,
//            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
//            'verify'        => env('CORE_SERVICE_VERIFY', false),
//            'http_errors'   => env('CORE_SERVICE_HTTP_ERRORS', false),
//        ]);
//        $offerRedemption = (new CoreServiceImpl($client))
//            ->offerRedemption($redemption);
    }
}
