<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Redemption;
use Illuminate\Events\Dispatcher;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\OfferRedemption;
use Tests\TestCase;

class RedemptionObserverTest extends TestCase
{
    public function testCreating()
    {
        /** @var OfferRedemption $offerRedemptionMock */
        $offerRedemptionMock = $this->createMock(OfferRedemption::class);
        /** @var CoreService $coreServiceImplMock */
        $coreServiceImplMock = $this->createMock(CoreService::class);
        /** @var Dispatcher $eventsDispatcher */
        $eventsDispatcher = $this->createMock(Dispatcher::class);
        /** @var Redemption $redemption */
        $redemption = $this->createMock(Redemption::class);

        $offerRedemptionMock->expects($this->once())->method('handle')->willReturn(true);
        $coreServiceImplMock->expects($this->once())->method('offerRedemption')->willReturn($offerRedemptionMock);
        $eventsDispatcher->expects($this->once())->method('listen');

        (new RedemptionObserver($eventsDispatcher, $coreServiceImplMock))
            ->creating($redemption);
    }
}
