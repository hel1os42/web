<?php

namespace OmniSynapse\WebHookService\Events;

class OfferDeletedEvent extends OfferEvent
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return self::EVENT_NAME_OFFER_DELETED;
    }
}
