<?php

namespace Tests\Unit\OmniSynapse\CoreService\Observers;

use Illuminate\Bus\Dispatcher;
use OmniSynapse\CoreService\AbstractJob;
use PHPUnit_Framework_MockObject_MockObject;
use Tests\TestCase;

/**
 * Class AbstractObserversTestCase
 * NS: Tests\Unit\OmniSynapse\CoreService\Observers
 */
abstract class AbstractObserversTestCase extends TestCase
{
    private $dispatherMock;

    /**
     * @return Dispatcher | PHPUnit_Framework_MockObject_MockObject
     *
     * @throws \PHPUnit_Framework_Exception
     */
    protected function getDispatcherMock()
    {
        if (null === $this->dispatherMock) {
            $this->mockDispatcher();
        }

        return $this->dispatherMock;
    }

    protected function mockDispatcherToDispatch(AbstractJob $expectedJob)
    {
        $this->getDispatcherMock();

        $this->dispatherMock
            ->expects($this->once())->method('dispatch')
            ->willReturnCallback(function (AbstractJob $dispatchedJob) use ($expectedJob) {
                $this->assertEquals($expectedJob, $dispatchedJob);
            });
    }

    /**
     * @return void
     * @throws \PHPUnit_Framework_Exception
     */
    protected function mockDispatcher(): void
    {
        $this->dispatherMock = $this->createMock(Dispatcher::class);
        app()->instance(Dispatcher::class, $this->dispatherMock);
    }
}
