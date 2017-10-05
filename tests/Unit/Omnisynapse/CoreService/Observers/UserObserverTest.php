<?php

namespace OmniSynapse\CoreService\Observers;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Exception\RequestException;
use OmniSynapse\CoreService\Job\UserCreated;
use Tests\Unit\OmniSynapse\CoreService\Observers\AbstractObserversTestCase;

class UserObserverTest extends AbstractObserversTestCase
{
    public function testCreating()
    {
        /** @var UserCreated $userCreatedMock */
        $userCreatedMock = $this->createMock(UserCreated::class);
        $this->mockDispatcherToDispatch($userCreatedMock);

        $user = $this->createMock(User::class);

        $coreServiceImplMock = $this->createMock(CoreService::class);
        $coreServiceImplMock
            ->expects($this->once())
            ->method('userCreated')
            ->withConsecutive([$user])
            ->willReturn($userCreatedMock);

        // Testing
        (new UserObserver($coreServiceImplMock))
            ->created($user);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException
     */
    public function testCreatingWithException()
    {
        /** @var UserCreated $userCreatedMock */
        $userCreatedMock = $this->createMock(UserCreated::class);
        $responseMock    = $this->createMock(Response::class);
        $user            = $this->createMock(User::class);

        $coreServiceImplMock = $this->createMock(CoreService::class);
        $coreServiceImplMock
            ->expects($this->once())
            ->method('userCreated')
            ->withConsecutive([$user])
            ->willReturn($userCreatedMock);

        $this->getDispatcherMock()
            ->expects($this->once())->method('dispatch')
            ->willThrowException(new RequestException($userCreatedMock, $responseMock));

        // Testing
        (new UserObserver($coreServiceImplMock))
            ->created($user);
    }
}
