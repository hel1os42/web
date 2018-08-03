<?php

namespace OmniSynapse\CoreService\Response;

use App\Events\UserEvent;

/**
 * Class Event
 * @package OmniSynapse\CoreService\Response
 */
class EventResponse extends BaseResponse
{
    /**
     * @static bool
     */
    protected static $hasEmptyBody = true;

    /**
     * @var UserEvent
     */
    private $event;

    /**
     * Event constructor.
     *
     * @param UserEvent $event
     */
    public function __construct(UserEvent $event)
    {
        $this->event = $event;
    }
}
