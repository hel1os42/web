<?php

namespace Tests\Integration\CoreService\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreServiceImpl;
use OmniSynapse\CoreService\Response\User;
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

        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "id"          => $userId,
            "username"    => $name,
            'referrer_id' => $referrerId,
            "level"       => $faker->randomDigitNotNull,
            "points"      => $faker->randomDigitNotNull,
            "wallets"     => [
                "currency" => "NAU",
                "address"  => md5($faker->uuid),
                "balance"  => $faker->randomFloat(),
            ],
            "created_at"  => Carbon::now()->format('Y-m-d H:i:sO'),
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn($response);

        (new CoreServiceImpl([
            'base_uri'      => env('CORE_SERVICE_BASE_URL', ''),
            'verify'        => (boolean)env('CORE_SERVICE_VERIFY', false),
            'http_errors'   => (boolean)env('CORE_SERVICE_HTTP_ERRORS', false),
        ]))
            ->setClient($client)
            ->userCreated($user)
            ->handle();

        $eventCalled = 0;
        \Event::listen(User::class, function ($createdUser) use ($name, &$eventCalled) {
            $this->assertEquals($createdUser->getName(), $name, 'User name is not equals to request name.');
            $eventCalled++;
        });

        $this->assertTrue($eventCalled > 0, 'Can not listen User created response event.');
    }
}
