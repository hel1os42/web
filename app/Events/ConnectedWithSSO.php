<?php

namespace App\Events;

use App\Models\IdentityProvider;
use App\Models\User;

/**
 * Class ConnectedWithSSO
 * @package App\Events
 */
class ConnectedWithSSO extends UserEvent
{
    /**
     * @var string
     */
    private $eventName;

    /**
     * ConnectedWithSSO constructor.
     *
     * @param User   $user
     * @param string $eventName
     */
    public function __construct(User $user, string $eventName)
    {
        parent::__construct($user);

        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->eventName;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return '';
    }

    /**
     * @param User   $user
     * @param string $providerName
     * @return ConnectedWithSSO
     */
    public static function make(User $user, string $providerName): self
    {
        return new self($user, self::getAppropriateEventName($providerName));
    }

    /**
     * @return array
     */
    public static function getEventMapping(): array
    {
        return [
            IdentityProvider::PROVIDER_FACEBOOK  => self::EVENT_CONNECTED_WITH_FACEBOOK,
            IdentityProvider::PROVIDER_TWITTER   => self::EVENT_CONNECTED_WITH_TWITTER,
            IdentityProvider::PROVIDER_VK        => self::EVENT_CONNECTED_WITH_VK,
            IdentityProvider::PROVIDER_INSTAGRAM => self::EVENT_CONNECTED_WITH_INSTAGRAM,
        ];
    }

    /**
     * @param string $providerName
     * @return string
     */
    public static function getAppropriateEventName(string $providerName): string
    {
        return array_get(self::getEventMapping(), $providerName, '?');
    }
}
