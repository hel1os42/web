<?php

namespace Tests\Integration\CoreService\Jobs;

use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\User;
use OmniSynapse\CoreService\Response\Wallet;
use Tests\TestCase;

class UserCreatedTest extends TestCase
{
    /**
     * Test UserCreated JOB.
     */
    public function testUserCreated()
    {
        $faker            = Faker::create();
        $referrer         = [
            'id' => $faker->uuid,
        ];

        $wallet           = new Wallet();
        $wallet->currency = 'NAU';
        $wallet->address  = $faker->uuid;
        $wallet->balance  = $faker->randomFloat();

        $createdAt        = Carbon::parse($faker->time());
        $user             = [
            'id'          => $faker->uuid,
            'username'    => $faker->name,
            'referrerId'  => $referrer['id'],
            'level'       => $faker->randomDigitNotNull,
            'points'      => $faker->randomDigitNotNull,
            'wallets'     => [
                $wallet
            ],
            'createdAt'   => $createdAt->format('Y-m-d H:i:sO'),
        ];

        $referrerMock = \Mockery::mock(\App\Models\User::class);
        $referrerMock->shouldReceive('getId')->once()->andReturn($referrer['id']);

        $userMock = \Mockery::mock(\App\Models\User::class);
        $userMock->shouldReceive('getId')->once()->andReturn($user['id']);
        $userMock->shouldReceive('getName')->once()->andReturn($user['username']);
        $userMock->shouldReceive('getReferrer')->once()->andReturn($referrerMock);

        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'id'           => $user['id'],
            'username'     => $user['username'],
            'referrer_id'  => $user['referrerId'],
            'level'        => $user['level'],
            'points'       => $user['points'],
            'wallets'      => $user['wallets'],
            'created_at'   => $user['createdAt'],
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(User::class, function ($response) use ($user, $createdAt, &$eventCalled) {
            $this->assertEquals($response->getId(), $user['id'], 'User: id');
            $this->assertEquals($response->getUsername(), $user['username'], 'User: username');
            $this->assertEquals($response->getReferrerId(), $user['referrerId'], 'User: referrer_id');
            $this->assertEquals($response->getLevel(), $user['level'], 'User: level');
            $this->assertEquals($response->getPoints(), $user['points'], 'User: points');
            $this->assertEquals($response->getWallets(), $user['wallets'], 'User: wallets');
            $this->assertEquals($response->getCreatedAt(), $createdAt, 'User: created_at');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->userCreated($userMock)
             ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen User response event.');
    }
}
