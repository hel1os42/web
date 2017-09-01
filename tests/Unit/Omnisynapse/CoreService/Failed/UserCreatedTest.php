<?php

namespace OmniSynapse\CoreService\Failed;

use Faker\Factory as Faker;
use OmniSynapse\CoreService\FailedJob\UserCreated;
use Tests\TestCase;

class UserCreatedTest extends TestCase
{
    public function testFailedResponse()
    {
        $faker = Faker::create();

        $exception = new \Exception;
        $user      = [
            'id' => $faker->uuid,
        ];

        $userMock = \Mockery::mock(\App\Models\User::class);
        $userMock->shouldReceive('getId')->andReturn($user['id']);

        $userCreated = (new UserCreated($exception, $userMock));

        $this->assertEquals($user['id'], $userCreated->getUser()->getId());
        $this->assertEquals($exception, $userCreated->getException());
    }
}
