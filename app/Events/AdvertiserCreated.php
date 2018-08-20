<?php

namespace App\Events;

/**
 * Class AdvertiserCreated
 * @package App\Events
 */
class AdvertiserCreated extends UserEvent
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return self::EVENT_ADVERTISER_CREATED;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return '';
    }
}
