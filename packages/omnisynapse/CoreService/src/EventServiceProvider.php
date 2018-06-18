<?php

namespace OmniSynapse\CoreService;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OmniSynapse\CoreService\Listeners\UserEventSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventSubscriber::class,
    ];
}
