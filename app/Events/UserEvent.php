<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserEvent
 *
 * @package App\Events
 */
abstract class UserEvent
{
    use SerializesModels;

    public const EVENT_ADVERTISER_CREATED = 'advertiser_created';
    public const EVENT_BROUGHT_FRIEND     = 'brought_friend';
    public const EVENT_EMAIL_CONFIRMED    = 'email_confirmed';
    public const EVENT_CONNECTED_WITH_SSO = 'connected_with_sso';

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return array
     */
    abstract public function getData(): array;
}
