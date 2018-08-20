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
     * @return string
     */
    public function getParameter(): string
    {
        return '';
    }
}
