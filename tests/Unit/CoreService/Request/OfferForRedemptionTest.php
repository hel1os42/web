<?php

namespace Tests\Unit\CoreService\Request;

use App\Models\Redemption;
use OmniSynapse\CoreService\Request\OfferForRedemption;
use Tests\TestCase;
use Faker\Factory as Faker;

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
        $jsonSerialize            = [
            'user_id' => $userId,
        ];

        /*
         * Compare json strings
         */
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($redemptionCreatedRequest->jsonSerialize()), 'jsonSerialize');
    }
}
