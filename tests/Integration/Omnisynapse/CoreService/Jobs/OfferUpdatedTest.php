<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Request\Offer\Geo;
use OmniSynapse\CoreService\Request\Offer\Limits;
use OmniSynapse\CoreService\Request\Offer\Point;
use Tests\TestCase;

class OfferUpdatedTest extends TestCase
{
    /**
     * OfferUpdated JOB test.
     *
     * @return void
     */
    public function testOfferUpdated()
    {
        $faker = Faker::create();

        /*
         * Account
         */
        $account     = [
            'id'       => $faker->uuid,
            'owner_id' => $faker->uuid,
        ];
        $accountMock = \Mockery::mock(Account::class);
        $accountMock->shouldReceive('getId')->andReturn($account['id']);
        $accountMock->shouldReceive('getOwnerId')->once()->andReturn($account['owner_id']);

        /*
         * Offer
         */
        $offerDateTimes = [
            'startDate' => Carbon::today()->subMonth(),
            'endDate'   => Carbon::today()->addMonth(),
            'startTime' => Carbon::parse($faker->time()),
            'endTime'   => Carbon::parse($faker->time()),
        ];
        $offer          = [
            'id'          => $faker->uuid,
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
        $offerMock->shouldReceive('getId')->once()->andReturn($offer['id']);
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

        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            'id'          => $offer['id'],
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
            $account,
            $offer,
            $offerDateTimes,
            $geo,
            $limits,
            &$eventCalled)
        {
            $this->assertEquals($offer['id'], $response->getId(), 'Offer id');
            $this->assertEquals($account['owner_id'], $response->getOwnerId(), 'Offer owner_id');
            $this->assertEquals($offer['name'], $response->getName(), 'Offer name');
            $this->assertEquals($offer['description'], $response->getDescription(), 'Offer description');
            $this->assertEquals($offer['categoryId'], $response->getCategoryId(), 'Offer category_id');
            $this->assertEquals($geo->jsonSerialize(), $response->getGeo()->jsonSerialize(), 'Offer GEO');
            $this->assertEquals($limits->jsonSerialize(), $response->getLimits()->jsonSerialize(), 'Offer id');
            $this->assertEquals($offer['reward'], $response->getReward(), 'Offer reward');
            $this->assertEquals($offerDateTimes['startDate'], $response->getStartDate(), 'Offer start_date');
            $this->assertEquals($offerDateTimes['endDate'], $response->getEndDate(), 'Offer end_date');
            $this->assertEquals($offerDateTimes['startTime'], $response->getStartTime(), 'Offer start_time');
            $this->assertEquals($offerDateTimes['endTime'], $response->getEndTime(), 'Offer end_time');
            $eventCalled++;
        });

        $exceptionEventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\FailedJob\OfferUpdated::class, function () use(&$exceptionEventCalled) {
            $exceptionEventCalled++;
        });

        $offerUpdated = $this->app->make(CoreService::class)
            ->setClient($clientMock)
            ->offerUpdated($offerMock);

        $offerUpdated->handle();
        $offerUpdated->failed((new \Exception));

        $this->assertEquals( 1, $eventCalled, 'Can not listen Offer event.');
        $this->assertEquals(1, $exceptionEventCalled, 'Can not listen OfferUpdated failed job.');

        $this->assertEquals([
            'coreService',
            'requestObject',
            'offer',
        ], $offerUpdated->__sleep());
    }
}
