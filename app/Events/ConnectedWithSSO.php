<?php

namespace App\Events;

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
    private $providerName;

    /**
     * ConnectedWithSSO constructor.
     *
     * @param User   $user
     * @param string $providerName
     */
    public function __construct(User $user, string $providerName)
    {
        parent::__construct($user);

        $this->providerName = $providerName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::EVENT_CONNECTED_WITH_SSO;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'user_id'       => $this->user->getKey(),
            'provider_name' => $this->providerName,
        ];
    }
}
