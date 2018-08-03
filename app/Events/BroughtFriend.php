<?php

namespace App\Events;

use App\Models\User;

/**
 * Class BroughtFriend
 * @package App\Events
 */
class BroughtFriend extends UserEvent
{

    /**
     * @var User
     */
    private $referral;

    /**
     * BroughtFriend constructor.
     * @param User $user
     * @param User $referral
     */
    public function __construct(User $user, User $referral)
    {
        parent::__construct($user);

        $this->referral = $referral;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::EVENT_BROUGHT_FRIEND;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->referral->getKey();
    }
}
