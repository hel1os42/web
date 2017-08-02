<?php

namespace Tests\Integration\CoreService\Jobs;

use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;
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
        $referrerMock->shouldReceive('getId')->atLeast(1)->andReturn($referrer['id']);

        $userMock = \Mockery::mock(\App\Models\User::class);
        $userMock->shouldReceive('getId')->atLeast(1)->andReturn($user['id']);
        $userMock->shouldReceive('getName')->atLeast(1)->andReturn($user['username']);
        $userMock->shouldReceive('getReferrer')->atLeast(1)->andReturn($referrerMock);

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
            $this->assertEquals($user['id'], $response->getId(), 'User: id');
            $this->assertEquals($user['username'], $response->getUsername(), 'User: username');
            $this->assertEquals($user['referrerId'], $response->getReferrerId(), 'User: referrer_id');
            $this->assertEquals($user['level'], $response->getLevel(), 'User: level');
            $this->assertEquals($user['points'], $response->getPoints(), 'User: points');
            $this->assertEquals($user['wallets'], $response->getWallets(), 'User: wallets');
            $this->assertEquals($createdAt, $response->getCreatedAt(), 'User: created_at');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->userCreated($userMock)
             ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen User response event.');

        /*
         * Testing server error
         */
        $status        = $faker->randomElement([404, 403, 500, 503, 504]);
        $errorResponse = new Response($status, [
            'Content-Type' => 'text/plain',
        ]);
        $this->sendRequestAndAssertException($errorResponse, $userMock, $status);

        /*
         * Testing wrong JSON response
         */
        $status        = 200;
        $errorResponse = new Response($status, [
            'Content-Type' => 'application/json',
        ]);
        $this->sendRequestAndAssertException($errorResponse, $userMock, $status);
    }

    /**
     * @param Response $response
     * @param MockInterface $userMock
     * @param int $status
     */
    private function sendRequestAndAssertException($response, $userMock, $status)
    {
        $errorClientMock = \Mockery::mock(Client::class);
        $errorClientMock->shouldReceive('request')->once()->andReturn($response);

        try {
            $this->app->make(CoreService::class)
                ->setClient($errorClientMock)
                ->userCreated($userMock)
                ->handle();
        } catch (RequestException $e) {
            $this->assertEquals($status, $e->getCode(), 'status');
            $this->assertContains($response->getReasonPhrase(), $e->getMessage(), 'error text');
        }
    }
}
