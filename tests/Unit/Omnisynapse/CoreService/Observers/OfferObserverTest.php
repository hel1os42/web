<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\OfferCreated;
use OmniSynapse\CoreService\Job\OfferUpdated;
use Tests\Unit\OmniSynapse\CoreService\Observers\AbstractObserversTestCase;

class OfferObserverTest extends AbstractObserversTestCase
{
    public function testCreating()
    {
        $offerCreatedMock = $this->createMock(OfferCreated::class);
        $this->mockDispatcherToDispatch($offerCreatedMock);

        $offer               = $this->createMock(Offer::class);
        $coreServiceImplMock = $this->createMock(CoreService::class);

        $coreServiceImplMock
            ->expects($this->once())
            ->method('offerCreated')
            ->withConsecutive([$offer])
            ->willReturn($offerCreatedMock);

        // Testing
        (new OfferObserver($coreServiceImplMock))
            ->creating($offer);
    }

    public function testUpdating()
    {
        /** @var OfferUpdated $offerUpdatedMock */
        $offerUpdatedMock = $this->createMock(OfferUpdated::class);
        $this->mockDispatcherToDispatch($offerUpdatedMock);

        $offer = $this->createMock(Offer::class);

        $coreServiceImplMock = $this->createMock(CoreService::class);
        $coreServiceImplMock
            ->expects($this->once())
            ->method('offerUpdated')
            ->withConsecutive([$offer])
            ->willReturn($offerUpdatedMock);

        // Testing
        (new OfferObserver($coreServiceImplMock))
            ->updating($offer);
    }
}
