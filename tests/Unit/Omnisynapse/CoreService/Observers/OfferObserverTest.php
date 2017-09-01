<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Offer;
use Illuminate\Support\Testing\Fakes\BusFake;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferUpdated;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class OfferObserverTest extends TestCase
{
    public function testCreating()
    {
        $coreServiceImplMock = \Mockery::mock(CoreService::class);
        $coreServiceImplMock->shouldReceive('offerCreated')->once()->andReturn(\Mockery::mock(OfferCreated::class));

        $this->app->singleton(CoreService::class, function () use($coreServiceImplMock) {
            return $coreServiceImplMock;
        });

        $this->app->singleton(\Illuminate\Contracts\Bus\Dispatcher::class, function () {
            return new BusFake();
        });

        (new OfferObserver())
            ->creating(\Mockery::mock(Offer::class));

        Bus::assertDispatched(get_class(\Mockery::mock(OfferCreated::class)));
    }

    public function testUpdating()
    {
        $coreServiceImplMock = \Mockery::mock(CoreService::class);
        $coreServiceImplMock->shouldReceive('offerUpdated')->once()->andReturn(\Mockery::mock(OfferUpdated::class));

        $this->app->singleton(CoreService::class, function () use($coreServiceImplMock) {
            return $coreServiceImplMock;
        });

        $this->app->singleton(\Illuminate\Contracts\Bus\Dispatcher::class, function () {
            return new BusFake();
        });

        (new OfferObserver())
            ->updating(\Mockery::mock(Offer::class));

        Bus::assertDispatched(get_class(\Mockery::mock(OfferUpdated::class)));
    }
}
