<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Offer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Limits;
use OmniSynapse\CoreService\Request\Offer\Point;
use Tests\TestCase;

class OfferCreatedTest extends TestCase
{
    /**
     * OfferCreated JOB test.
     *
     * @return void
     */
    public function testOfferCreated()
    {
        $faker = Faker::create();

        /*
         * Account
         */
        $account = [
            'id'       => $faker->uuid,
            'owner_id' => $faker->uuid,
        ];
        $accountMock   = \Mockery::mock(Account::class);
        $accountMock->shouldReceive('getId')->andReturn($account['id']);
        $accountMock->shouldReceive('getOwnerId')->once()->andReturn($account['owner_id']);

        /*
         * Offer
         */
        $offerId        = $faker->uuid;
        $offerDateTimes = [
            'startDate' => Carbon::today()->subMonth(),
            'endDate'   => Carbon::today()->addMonth(),
            'startTime' => Carbon::parse($faker->time()),
            'endTime'   => Carbon::parse($faker->time()),
        ];
        $offer          = [
            'name'        => $faker->name,
            'description' => $faker->text,
            'categoryId'  => $faker->uuid,
            'reward'      => $faker->randomFloat(),

            'radius'      => $faker->randomDigitNotNull,
            'city'        => $faker->city,
            'country'     => $faker->country,
            'lat'         => $faker->latitude,
            'lon'         => $faker->longitude,

            'offers'      => $faker->randomDigitNotNull,
            'perDay'      => $faker->randomDigitNotNull,
            'perUser'     => $faker->randomDigitNotNull,
            'minLevel'    => $faker->randomDigitNotNull,
        ];

        $offerMock = \Mockery::mock(Offer::class);
        $offerMock->shouldReceive('getLabel')->once()->andReturn($offer['name']);
        $offerMock->shouldReceive('getDescription')->once()->andReturn($offer['description']);
        $offerMock->shouldReceive('getCategoryId')->once()->andReturn($offer['categoryId']);
        $offerMock->shouldReceive('getReward')->once()->andReturn($offer['reward']);
        $offerMock->shouldReceive('getStartDate')->once()->andReturn($offerDateTimes['startDate']);
        $offerMock->shouldReceive('getFinishDate')->once()->andReturn($offerDateTimes['endDate']);
        $offerMock->shouldReceive('getStartTime')->once()->andReturn($offerDateTimes['startTime']);
        $offerMock->shouldReceive('getFinishTime')->once()->andReturn($offerDateTimes['endTime']);
        $offerMock->shouldReceive('getLatitude')->once()->andReturn($offer['lat']);
        $offerMock->shouldReceive('getLongitude')->once()->andReturn($offer['lon']);
        $offerMock->shouldReceive('getRadius')->once()->andReturn($offer['radius']);
        $offerMock->shouldReceive('getCity')->once()->andReturn($offer['city']);
        $offerMock->shouldReceive('getCountry')->once()->andReturn($offer['country']);
        $offerMock->shouldReceive('getMaxCount')->once()->andReturn($offer['offers']);
        $offerMock->shouldReceive('getMaxPerDay')->once()->andReturn($offer['perDay']);
        $offerMock->shouldReceive('getMaxForUser')->once()->andReturn($offer['perUser']);
        $offerMock->shouldReceive('getUserLevelMin')->once()->andReturn($offer['minLevel']);
        $offerMock->shouldReceive('getAccount')->once()->andReturn($accountMock);

        /*
         * GEO
         */
        $point = new Point($offer['lat'], $offer['lon']);
        $geo   = new Geo($point, $offer['radius'], $offer['city'], $offer['country']);

        /*
         * Limits
         */
        $limits = new Limits($offer['offers'], $offer['perDay'], $offer['perUser'], $offer['minLevel']);

        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'id'          => $offerId,
            'owner_id'    => $account['owner_id'],
            'name'        => $offer['name'],
            'description' => $offer['description'],
            'category_id' => $offer['categoryId'],
            'geo'         => $geo->jsonSerialize(),
            'limits'      => $limits->jsonSerialize(),
            'reward'      => $offer['reward'],
            'start_date'  => $offerDateTimes['startDate']->format('Y-m-dO'),
            'end_date'    => $offerDateTimes['endDate']->format('Y-m-dO'),
            'start_time'  => $offerDateTimes['startTime']->format('H:i:sO'),
            'end_time'    => $offerDateTimes['endTime']->format('H:i:sO'),
        ]));

        $clientMock = \Mockery::mock(Client::class);
        $clientMock->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Offer::class, function ($response) use
        (
            $offerId,
            $account,
            $offer,
            $offerDateTimes,
            $geo,
            $limits,
            &$eventCalled)
        {
            $this->assertEquals($response->getId(), $offerId, 'Offer id');
            $this->assertEquals($response->getOwnerId(), $account['owner_id'], 'Offer owner_id');
            $this->assertEquals($response->getName(), $offer['name'], 'Offer name');
            $this->assertEquals($response->getDescription(), $offer['description'], 'Offer description');
            $this->assertEquals($response->getCategoryId(), $offer['categoryId'], 'Offer category_id');
            $this->assertEquals($response->getGeo()->jsonSerialize(), $geo->jsonSerialize(), 'Offer GEO');
            $this->assertEquals($response->getLimits()->jsonSerialize(), $limits->jsonSerialize(), 'Offer id');
            $this->assertEquals($response->getReward(), $offer['reward'], 'Offer reward');
            $this->assertEquals($response->getStartDate(), $offerDateTimes['startDate'], 'Offer start_date');
            $this->assertEquals($response->getEndDate(), $offerDateTimes['endDate'], 'Offer end_date');
            $this->assertEquals($response->getStartTime(), $offerDateTimes['startTime'], 'Offer start_time');
            $this->assertEquals($response->getEndTime(), $offerDateTimes['endTime'], 'Offer end_time');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->offerCreated($offerMock)
            ->handle();

        $this->assertEquals( 1, $eventCalled,  'Can not listen Offer event.');
    }
}
