<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Offer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
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
        $accountId = $faker->uuid;
        $ownerId   = $faker->uuid;
        $account   = \Mockery::mock(Account::class);
        $account->shouldReceive('getId')->andReturn($accountId);
        $account->shouldReceive('getOwnerId')->once()->andReturn($ownerId);

        /*
         * Offer
         */

        $offerId     = $faker->uuid;
        $name        = $faker->name;
        $description = $faker->text;
        $categoryId  = $faker->uuid;
        $reward      = $faker->randomFloat();
        $startDate   = Carbon::now()->subMonth();
        $endDate     = Carbon::now()->addMonth();
        $startTime   = Carbon::parse($faker->time());
        $endTime     = Carbon::parse($faker->time());

        $radius      = $faker->randomDigitNotNull;
        $city        = $faker->city;
        $country     = $faker->country;
        $lat         = $faker->latitude;
        $lon         = $faker->longitude;

        $offers      = $faker->randomDigitNotNull;
        $perDay      = $faker->randomDigitNotNull;
        $perUser     = $faker->randomDigitNotNull;
        $minLevel    = $faker->randomDigitNotNull;

        $offer       = \Mockery::mock(Offer::class);
        $offer->shouldReceive('getId')->andReturn($offerId);
        $offer->shouldReceive('getLabel')->once()->andReturn($name);
        $offer->shouldReceive('getDescription')->once()->andReturn($description);
        $offer->shouldReceive('getCategoryId')->once()->andReturn($categoryId);
        $offer->shouldReceive('getReward')->once()->andReturn($reward);
        $offer->shouldReceive('getStartDate')->once()->andReturn($startDate);
        $offer->shouldReceive('getFinishDate')->once()->andReturn($endDate);
        $offer->shouldReceive('getStartTime')->once()->andReturn($startTime);
        $offer->shouldReceive('getFinishTime')->once()->andReturn($endTime);
        $offer->shouldReceive('getLatitude')->once()->andReturn($lat);
        $offer->shouldReceive('getLongitude')->once()->andReturn($lon);
        $offer->shouldReceive('getRadius')->once()->andReturn($radius);
        $offer->shouldReceive('getCity')->once()->andReturn($city);
        $offer->shouldReceive('getCountry')->once()->andReturn($country);
        $offer->shouldReceive('getMaxCount')->once()->andReturn($offers);
        $offer->shouldReceive('getMaxPerDay')->once()->andReturn($perDay);
        $offer->shouldReceive('getMaxForUser')->once()->andReturn($perUser);
        $offer->shouldReceive('getUserLevelMin')->once()->andReturn($minLevel);
        $offer->shouldReceive('getAccount')->once()->andReturn($account);

        /*
         * Test JOB
         */
        $response = new Response(200, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "name" => $name,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Offer::class, function ($response) use ($name, &$eventCalled) {
            $this->assertEquals($response->getName(), $name, 'Offer name is not equals to request name.');
            $eventCalled++;
        });

        $this->app->make(CoreService::class)
            ->setClient($client)
            ->offerCreated($offer)
            ->handle();

        $this->assertEquals( 1, $eventCalled, 'Can not listen Offer updated event.');
    }
}
