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
     * @return array
     */
    public function getData(): array
    {
        return [
            'user_id' => $this->user->getKey(),
        ];
    }
}
