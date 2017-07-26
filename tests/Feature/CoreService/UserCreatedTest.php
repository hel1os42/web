<?php

namespace Tests\Feature\CoreService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OmniSynapse\CoreService\Job\UserCreated;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class UserCreatedTest extends TestCase
{
    /**
     * Test UserCreated JOB.
     */
    public function testUserCreated()
    {
        $random   = str_random(30);
        $referrer = User::first();
        $user     = User::create([
            'name'        => $random,
            'email'       => $random,
            'password'    => Hash::make($random),
            'referrer_id' => null !== $referrer ? $referrer->getId() : null,
        ]);

        $coreService = app()->make('OmniSynapse\CoreService\CoreServiceInterface');

        Queue::push($coreService->userCreated($user));
        Queue::fake();
        Queue::assertPushed(UserCreated::class, function ($userCreatedResponse) use ($user) {
            return $userCreatedResponse->getId() === $user->getId();
        });
    }
}
