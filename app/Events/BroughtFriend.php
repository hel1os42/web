<?php

namespace App\Events;

/**
 * Class BroughtFriend
 * @package App\Events
 */
class BroughtFriend extends UserEvent
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return self::EVENT_BROUGHT_FRIEND;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'user_id'     => $this->user->getKey(),
            'referrer_id' => $this->user->getReferrerId(),
        ];
    }
}
