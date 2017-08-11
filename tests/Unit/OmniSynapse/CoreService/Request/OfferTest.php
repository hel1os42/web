<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Tests\TestCase;

class OfferTest extends TestCase
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
        $offerRequest = new \OmniSynapse\CoreService\Request\Offer($offer);
        $expected     = [
            'owner_id'          => $account->getOwnerId(),
            'name'              => $name,
            'description'       => $description,
            'category_id'       => $categoryId,
            'geo'               => $offerRequest->geo->jsonSerialize(), // don't have to be checked
            'limits'            => $offerRequest->limits->jsonSerialize(), // don't have to be checked
            'reward'            => $reward,
            'start_date'        => $startDate->toDateString(),
            'end_date'          => $endDate->toDateString(),
            'start_time'        => $startTime->toTimeString(),
            'end_time'          => $endTime->toTimeString(),
        ];

        /*
         * Compare arrays
         */
        $this->assertEquals($expected, $offerRequest->jsonSerialize(), 'Expected array is not equals with offerCreated array');
    }
}
