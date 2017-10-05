<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Job\SendNau;
use Tests\Unit\OmniSynapse\CoreService\Observers\AbstractObserversTestCase;

class TransactObserverTest extends AbstractObserversTestCase
{
    public function testCreating()
    {
        /** @var SendNau $sendNauMock */
        $sendNauMock = $this->createMock(SendNau::class);
        $this->mockDispatcherToDispatch($sendNauMock);

        $coreServiceImplMock = $this->createMock(CoreService::class);

        /** @var Transact $transactionMock */
        $transactionMock = $this->createMock(Transact::class);

        $transactionMock->expects($this->once())->method('isTypeP2p')->willReturn(true);

        $coreServiceImplMock
            ->expects($this->once())
            ->method('sendNau')
            ->withConsecutive([$transactionMock])
            ->willReturn($sendNauMock);

        // Testing
        (new TransactObserver($coreServiceImplMock))
            ->creating($transactionMock);
    }
}
