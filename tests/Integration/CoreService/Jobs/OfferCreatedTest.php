<?php

namespace Tests\Integration\CoreService\Jobs;

use App\Models\Account;
use App\Models\Offer;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreServiceImpl;
use Tests\TestCase;
use Faker\Factory as Faker;

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
        $accountId = $faker->uuid;
        $ownerId   = $faker->uuid;
        $account   = \Mockery::mock(Account::class);
        $account->shouldReceive('getId')->andReturn($accountId);
        $account->shouldReceive('getOwnerId')->andReturn($ownerId);

        /*
         * Offer
         */
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
        $offer->shouldReceive('getLabel')->andReturn($name);
        $offer->shouldReceive('getDescription')->andReturn($description);
        $offer->shouldReceive('getCategoryId')->andReturn($categoryId);
        $offer->shouldReceive('getReward')->andReturn($reward);
        $offer->shouldReceive('getStartDate')->andReturn($startDate);
        $offer->shouldReceive('getFinishDate')->andReturn($endDate);
        $offer->shouldReceive('getStartTime')->andReturn($startTime);
        $offer->shouldReceive('getFinishTime')->andReturn($endTime);
        $offer->shouldReceive('getLatitude')->andReturn($lat);
        $offer->shouldReceive('getLongitude')->andReturn($lon);
        $offer->shouldReceive('getRadius')->andReturn($radius);
        $offer->shouldReceive('getCity')->andReturn($city);
        $offer->shouldReceive('getCountry')->andReturn($country);
        $offer->shouldReceive('getMaxCount')->andReturn($offers);
        $offer->shouldReceive('getMaxPerDay')->andReturn($perDay);
        $offer->shouldReceive('getMaxForUser')->andReturn($perUser);
        $offer->shouldReceive('getUserLevelMin')->andReturn($minLevel);
        $offer->shouldReceive('getAccount')->andReturn($account);

        /*
         * Test JOB
         */
        $response = new Response(201, [
            'Content-Type' => 'application/json',
        ], \GuzzleHttp\json_encode([
            "name" => $name,
        ]));
        $client = \Mockery::mock(Client::class);
        $client->shouldReceive('request')->andReturn($response);

        $eventCalled = 0;
        \Event::listen(\OmniSynapse\CoreService\Response\Offer::class, function ($response) use ($name, &$eventCalled) {
            $this->assertEquals($response->getName(), $name, 'Offer name is not equals to request name.');
            $eventCalled++;
        });

        (new CoreServiceImpl())
            ->setClient($client)
            ->offerCreated($offer)
            ->handle();

        $this->assertEquals( 1, $eventCalled,  'Can not listen Offer event.');
    }
}
