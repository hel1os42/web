<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\Redemption;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Response\OfferForRedemption;
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

        $redemption = [
            'id'          => $faker->uuid,
            'userId'      => $faker->uuid,
            'offerId'     => $faker->uuid,
            'points'      => $faker->randomDigitNotNull,
            'rewardedId'  => $faker->uuid,
            'amount'      => $faker->randomFloat(),
            'fee'         => $faker->randomFloat(),
            'createdAt'   => Carbon::parse($faker->time()),
        ];

        $redemptionMock = \Mockery::mock(Redemption::class);
        $redemptionMock->shouldReceive('getId')->once()->andReturn($redemption['id']);
        $redemptionMock->shouldReceive('getUserId')->once()->andReturn($redemption['userId']);

        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'id'          => $redemption['id'],
            'offer_id'    => $redemption['offerId'],
            'user_id'     => $redemption['userId'],
            'points'      => $redemption['points'],
            'rewarded_id' => $redemption['rewardedId'],
            'amount'      => $redemption['amount'],
            'fee'         => $redemption['fee'],
            'created_at'  => $redemption['createdAt']->format('Y-m-d H:i:sO')
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(OfferForRedemption::class, function ($response) use ($redemption, &$eventCalled) {
            $this->assertEquals($redemption['id'], $response->getId(), 'Redemption: id');
            $this->assertEquals($redemption['offerId'], $response->getOfferId(), 'Redemption: offer_id');
            $this->assertEquals($redemption['userId'], $response->getUserId(), 'Redemption: user_id');
            $this->assertEquals($redemption['points'], $response->getPoints(), 'Redemption: points');
            $this->assertEquals($redemption['rewardedId'], $response->getRewardedId(), 'Redemption: rewarded_id');
            $this->assertEquals($redemption['amount'], $response->getAmount(), 'Redemption: amount');
            $this->assertEquals($redemption['fee'], $response->getFee(), 'Redemption: fee');
            $this->assertEquals($redemption['createdAt'], $response->getCreatedAt(), 'Redemption: created_at');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->offerRedemption($redemptionMock)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen OfferForRedemption response event.');
    }
}
