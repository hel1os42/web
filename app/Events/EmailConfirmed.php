<?php

namespace App\Events;

/**
 * Class EmailConfirmed
 * @package App\Events
 */
class EmailConfirmed extends UserEvent
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return self::EVENT_EMAIL_CONFIRMED;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'user_id' => $this->user->getKey(),
            'email'   => $this->user->getEmail(),
        ];
    }
}
