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

    public const EVENT_ADVERTISER_CREATED       = 'advertiser_created';
    public const EVENT_BROUGHT_FRIEND           = 'brought_friend';
    public const EVENT_EMAIL_CONFIRMED          = 'email_confirmed';
    public const EVENT_CONNECTED_WITH_FACEBOOK  = 'connected_with_facebook';
    public const EVENT_CONNECTED_WITH_TWITTER   = 'connected_with_twitter';
    public const EVENT_CONNECTED_WITH_VK        = 'connected_with_vk';
    public const EVENT_CONNECTED_WITH_INSTAGRAM = 'connected_with_instagram';

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
     * @return string
     */
    abstract public function getParameter(): string;

    /**
     * @return string
     */
    final public function getUserId(): string
    {
        return $this->user->getKey();
    }
}
