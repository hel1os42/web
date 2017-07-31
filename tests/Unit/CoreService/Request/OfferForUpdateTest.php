<?php

namespace Tests\Unit\CoreService\Request;

use App\Models\Account;
use App\Models\Offer;
use Carbon\Carbon;
use Tests\TestCase;
use Faker\Factory as Faker;

class OfferForUpdateTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        /*
         * Prepare Account mock
         */
        $account = $this->createMock(Account::class);
        $account->method('getOwnerId')->willReturn($faker->uuid);

        /*
         * Prepare Offer mock and params
         */
        $offer        = $this->createMock(Offer::class);
        $offerId      = $faker->uuid;
        $name         = $faker->name;
        $description  = $faker->text();
        $categoryId   = $faker->uuid;
        $radius       = $faker->randomDigitNotNull;
        $city         = $faker->city;
        $country      = $faker->country;
        $latitude     = $faker->latitude;
        $longitude    = $faker->longitude;
        $maxCount     = $faker->randomDigitNotNull;
        $maxPerDay    = $faker->randomDigitNotNull;
        $maxPerUser   = $faker->randomDigitNotNull;
        $userMinLevel = $faker->randomDigitNotNull;
        $reward       = $faker->randomFloat();
        $startDate    = Carbon::now()->subDays(30);
        $endDate      = Carbon::now()->addDays(30);
        $startTime    = $startDate->copy()->setTimeFromTimeString($faker->time());
        $endTime      = $startDate->copy()->setTimeFromTimeString($faker->time());

        /*
         * Set Offer methods
         */
        $offer->method('getId')->willReturn($offerId);
        $offer->method('getLabel')->willReturn($name);
        $offer->method('getDescription')->willReturn($description);
        $offer->method('getCategoryId')->willReturn($categoryId);
        $offer->method('getRadius')->willReturn($radius);
        $offer->method('getCity')->willReturn($city);
        $offer->method('getCountry')->willReturn($country);
        $offer->method('getLatitude')->willReturn($latitude);
        $offer->method('getLongitude')->willReturn($longitude);
        $offer->method('getMaxCount')->willReturn($maxCount);
        $offer->method('getMaxPerDay')->willReturn($maxPerDay);
        $offer->method('getMaxForUser')->willReturn($maxPerUser);
        $offer->method('getUserLevelMin')->willReturn($userMinLevel);
        $offer->method('getReward')->willReturn($reward);
        $offer->method('getStartDate')->willReturn($startDate);
        $offer->method('getFinishDate')->willReturn($endDate);
        $offer->method('getStartTime')->willReturn($startTime);
        $offer->method('getFinishTime')->willReturn($endTime);
        $offer->method('getAccount')->willReturn($account);

        /*
         * Create Offer request and prepare jsonSerialize for comparing
         */
        $offerCreatedRequest = new \OmniSynapse\CoreService\Request\OfferForUpdate($offer);
        $jsonSerialize       = [
            'owner_id'          => $account->getOwnerId(),
            'name'              => $name,
            'description'       => $description,
            'category_id'       => $categoryId,
            'geo'               => $offerCreatedRequest->geo->jsonSerialize(), // don't have to be checked
            'limits'            => $offerCreatedRequest->limits->jsonSerialize(), // don't have to be checked
            'reward'            => $reward,
            'start_date'        => $startDate->toDateString(),
            'end_date'          => $endDate->toDateString(),
            'start_time'        => $startTime->toTimeString(),
            'end_time'          => $endTime->toTimeString(),
        ];

        /*
         * Compare json strings
         */
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($offerCreatedRequest->jsonSerialize()), 'jsonSerialize');
    }
}
