<?php

namespace OmniSynapse\WebHookService\Events;

class ActivationCodeCreatedEvent extends ActivationCodeEvent
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return self::EVENT_NAME_CODE_CREATED;
    }
}
