<?php

namespace OmniSynapse\WebHookService\Events;

class OfferUpdatedEvent extends OfferEvent
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return self::EVENT_NAME_OFFER_UPDATED;
    }
}
