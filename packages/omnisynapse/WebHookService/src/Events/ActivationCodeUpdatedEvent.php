<?php

namespace OmniSynapse\WebHookService\Events;

class ActivationCodeUpdatedEvent extends ActivationCodeEvent
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return self::EVENT_NAME_CODE_UPDATED;
    }
}
