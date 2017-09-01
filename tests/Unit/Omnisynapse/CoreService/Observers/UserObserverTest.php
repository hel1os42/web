<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\User;
use Illuminate\Support\Testing\Fakes\BusFake;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\UserCreated;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class UserObserverTest extends TestCase
{
    public function testCreating()
    {
        $coreServiceImplMock = \Mockery::mock(CoreService::class);
        $coreServiceImplMock->shouldReceive('userCreated')->once()->andReturn(\Mockery::mock(UserCreated::class));

        $this->app->singleton(CoreService::class, function () use($coreServiceImplMock) {
            return $coreServiceImplMock;
        });

        $this->app->singleton(\Illuminate\Contracts\Bus\Dispatcher::class, function () {
            return new BusFake();
        });

        (new UserObserver())
            ->created(\Mockery::mock(User::class));

        Bus::assertDispatched(get_class(\Mockery::mock(UserCreated::class)));
    }
}
