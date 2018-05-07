<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Tests\TestCase;

class OfferForUpdateTest extends TestCase
{
    const DATE_FORMAT = 'Y-m-dTH:i:sO';
    const TIME_FORMAT = 'H:i:sO';

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

        $dates = [
            'startDate' => '2017-01-01T00:00:00+0300',
            'endDate' => '2017-01-01T23:59:59+0300',
        ];

        /*
         * Prepare Offer mock and params
         */
        $offer              = $this->createMock(Offer::class);
        $offerId            = $faker->uuid;
        $name               = $faker->name;
        $description        = $faker->text();
        $categoryId         = $faker->uuid;
        $radius             = $faker->randomDigitNotNull;
        $city               = $faker->city;
        $country            = $faker->country;
        $latitude           = $faker->latitude;
        $longitude          = $faker->longitude;
        $maxCount           = $faker->randomDigitNotNull;
        $maxPerDay          = $faker->randomDigitNotNull;
        $maxPerUser         = $faker->randomDigitNotNull;
        $maxPerUserPerDay   = $faker->randomDigitNotNull;
        $maxPerUserPerWeek  = $faker->randomDigitNotNull;
        $maxPerUserPerMonth = $faker->randomDigitNotNull;
        $userMinLevel       = $faker->randomDigitNotNull;
        $reward             = $faker->randomFloat();
        $startDate          = Carbon::parse($dates['startDate']);
        $endDate            = Carbon::parse($dates['endDate']);
        $status             = 'active';
        $reserved           = $faker->randomFloat();
        $points             = $faker->randomDigit();

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
        $offer->method('getMaxForUserPerDay')->willReturn($maxPerUserPerDay);
        $offer->method('getMaxForUserPerWeek')->willReturn($maxPerUserPerWeek);
        $offer->method('getMaxForUserPerMonth')->willReturn($maxPerUserPerMonth);
        $offer->method('getUserLevelMin')->willReturn($userMinLevel);
        $offer->method('getReward')->willReturn($reward);
        $offer->method('getStartDate')->willReturn($startDate);
        $offer->method('getFinishDate')->willReturn($endDate);
        $offer->method('getAccount')->willReturn($account);
        $offer->method('getStatus')->willReturn($status);
        $offer->method('getReserved')->willReturn($reserved);
        $offer->method('getPoints')->willReturn($points);

        /*
         * Create Offer request and prepare jsonSerialize for comparing
         */
        $offerForUpdateRequest = new OfferForUpdate($offer);
        $expected              = [
            'id'                => $offerId,
            'owner_id'          => $account->getOwnerId(),
            'name'              => $name,
            'description'       => $description,
            'category_id'       => $categoryId,
            'geo'               => $offerForUpdateRequest->geo->jsonSerialize(), // don't have to be checked
            'limits'            => $offerForUpdateRequest->limits->jsonSerialize(), // don't have to be checked
            'reward'            => $reward,
            'start_date'        => $dates['startDate'],
            'end_date'          => $dates['endDate'],
            'status'            => $status,
            'reserved'          => $reserved,
            'points'            => $points,
        ];

        /*
         * Compare arrays
         */
        $this->assertEquals($expected, $offerForUpdateRequest->jsonSerialize(), 'Expected array is not equals with offerCreated array');
    }
}
