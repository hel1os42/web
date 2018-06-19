<?php

namespace OmniSynapse\CoreService\Listeners;

use App\Events\AdvertiserCreated;
use App\Events\BroughtFriend;
use App\Events\ConnectedWithSSO;
use App\Events\EmailConfirmed;
use App\Events\UserEvent;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use OmniSynapse\CoreService\CoreService;

class UserEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var CoreService
     */
    private $coreService;

    /**
     * Create the event listener.
     *
     * @param CoreService $coreService
     */
    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            [
                AdvertiserCreated::class,
                EmailConfirmed::class,
                ConnectedWithSSO::class,
                BroughtFriend::class,
            ],
            __CLASS__ . '@handleEvent'
        );
    }

    /**
     * @param UserEvent $event
     */
    public function handleEvent(UserEvent $event)
    {
        dispatch($this->coreService->eventOccurred($event));
    }
}
