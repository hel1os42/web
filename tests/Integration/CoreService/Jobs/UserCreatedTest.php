<?php

namespace Tests\Integration\CoreService\Jobs;

use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\User;
use Tests\TestCase;

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
        $referrer->shouldReceive('getId')->once()->andReturn($referrerId);

        $user = \Mockery::mock(\App\Models\User::class);
        $user->shouldReceive('getId')->once()->andReturn($userId);
        $user->shouldReceive('getName')->once()->andReturn($name);
        $user->shouldReceive('getReferrer')->once()->andReturn($referrer);

        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "id" => $userId,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(User::class, function ($response) use ($userId, &$eventCalled) {
            $this->assertEquals($response->getId(), $userId, 'User name is not equals to request name.');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($client)
            ->userCreated($user)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen User response event.');
    }
}
