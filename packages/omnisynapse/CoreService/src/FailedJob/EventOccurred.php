<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Events\UserEvent;
use OmniSynapse\CoreService\FailedJob;
use Exception;

/**
 * Class EventOccurred
 * @package OmniSynapse\CoreService\FailedJob
 */
class EventOccurred extends FailedJob
{
    /**
     * @var UserEvent
     */
    private $event;

    /**
     * EventOccurred constructor.
     *
     * @param Exception $exception
     * @param UserEvent $event
     */
    public function __construct(\Exception $exception, UserEvent $event)
    {
        parent::__construct($exception);

        $this->event = $event;
    }
}
