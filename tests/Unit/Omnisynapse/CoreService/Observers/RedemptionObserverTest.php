<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Redemption;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\OfferRedemption;
use Tests\TestCase;

class RedemptionObserverTest extends TestCase
{
    public function testCreating()
    {
        $offerRedemptionMock = \Mockery::mock(OfferRedemption::class);
        $coreServiceImplMock = \Mockery::mock(CoreService::class);

        try {
            $offerRedemptionMock->shouldReceive('handle')->once()->andReturn(true);
            $coreServiceImplMock->shouldReceive('offerRedemption')->once()->andReturn($offerRedemptionMock);

            $allEventsCalled = true;
        } catch (\Mockery\Exception\InvalidCountException $e) {
            $allEventsCalled = false;
        }

        $this->app->singleton(CoreService::class, function () use($coreServiceImplMock) {
            return $coreServiceImplMock;
        });

        (new RedemptionObserver())
            ->creating(\Mockery::mock(Redemption::class));

        $this->assertTrue($allEventsCalled, 'not all observer methods was called');
    }
}
