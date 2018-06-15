<?php

namespace OmniSynapse\CoreService\Request;

use App\Events\UserEvent;
use JsonSerializable;

class EventRequest implements JsonSerializable
{
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

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'event_name' => $this->event->getName(),
            'data'       => $this->event->getData(),
        ];
    }
}