<?php

namespace Tests\Integration\CoreService\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use OmniSynapse\CoreService\CoreServiceImpl;
use Tests\TestCase;
use Faker\Factory as Faker;

class UserCreatedTest extends TestCase
{
    /**
     * Test UserCreated JOB.
     */
    public function testUserCreated()
    {
        $faker = Faker::create();

        $referrerId = $faker->uuid;
        $userId     = $faker->uuid;
        $name       = $faker->name;

        $referrer = \Mockery::mock(\App\Models\User::class);
        $referrer->shouldReceive('getId')->andReturn($referrerId);

        $user = \Mockery::mock(\App\Models\User::class);
        $user->shouldReceive('getId')->andReturn($userId);
        $user->shouldReceive('getName')->andReturn($name);
        $user->shouldReceive('getReferrer')->andReturn($referrer);

        $mockHandler = new MockHandler();
        $client      = new Client([
            'handler'       => $mockHandler,
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => env('CORE_SERVICE_HTTP_ERRORS', false),
        ]);
        $userCreated = (new CoreServiceImpl($client))
            ->userCreated($user);
    }
}
