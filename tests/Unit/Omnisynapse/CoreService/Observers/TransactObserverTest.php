<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Transact;
use Illuminate\Support\Testing\Fakes\BusFake;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\SendNau;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class TransactObserverTest extends TestCase
{
    public function testCreating()
    {
        $coreServiceImplMock = \Mockery::mock(CoreService::class);
        $coreServiceImplMock->shouldReceive('sendNau')->once()->andReturn(\Mockery::mock(SendNau::class));

        $this->app->singleton(CoreService::class, function () use($coreServiceImplMock) {
            return $coreServiceImplMock;
        });

        $this->app->singleton(\Illuminate\Contracts\Bus\Dispatcher::class, function () {
            return new BusFake();
        });

        $transactionMock = \Mockery::mock(Transact::class);
        $transactionMock->shouldReceive('isTypeP2p')->once()->andReturn(true);

        (new TransactObserver())
            ->creating($transactionMock);

        Bus::assertDispatched(get_class(\Mockery::mock(SendNau::class)));
    }
}
