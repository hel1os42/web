<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\NauModels\Redemption;
use Faker\Factory as Faker;
use Tests\TestCase;

class OfferForRedemptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        /*
         * Prepare params
         */
        $offerId = $faker->uuid;
        $userId  = $faker->uuid;

        /*
         * Prepare Redemption mock
         */
        $redemption = $this->createMock(Redemption::class);

        /*
         * Set Redemption methods
         */
        $redemption->method('getId')->willReturn($offerId);
        $redemption->method('getUserId')->willReturn($userId);

        /*
         * Create Offer request and prepare jsonSerialize for comparing
         */
        $redemptionCreatedRequest = new OfferForRedemption($redemption);
        $expected                 = [
            'user_id' => $userId,
        ];

        /*
         * Compare arrays
         */
        $this->assertEquals($expected, $redemptionCreatedRequest->jsonSerialize(), 'Expected array is not equals with redemptionCreated array');
    }
}
