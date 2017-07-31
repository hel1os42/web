<?php

namespace Tests\Unit\CoreService\Request;

use OmniSynapse\CoreService\Request\User;
use Tests\TestCase;
use Faker\Factory as Faker;

class UserTest extends TestCase
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
        $userId     = $faker->uuid;
        $name       = $faker->name;
        $referrerId = $faker->uuid;

        /*
         * Referrer relation
         */
        $referrer = $this->createMock(\App\Models\User::class);
        $referrer->method('getId')->willReturn($referrerId);

        /*
         * Prepare User mock
         */
        $user = $this->createMock(\App\Models\User::class);

        /*
         * Set User methods
         */
        $user->method('getId')->willReturn($userId);
        $user->method('getName')->willReturn($name);
        $user->method('getReferrer')->willReturn($referrer);

        /*
         * Create User request and prepare jsonSerialize for comparing
         */
        $userCreatedRequest = new User($user);
        $jsonSerialize      = [
            'id'          => $userId,
            'username'    => $name,
            'referrer_id' => $referrerId,
        ];

        /*
         * Compare json strings
         */
        $this->assertJsonStringEqualsJsonString(\GuzzleHttp\json_encode($jsonSerialize), \GuzzleHttp\json_encode($userCreatedRequest->jsonSerialize()), 'jsonSerialize');
    }
}
