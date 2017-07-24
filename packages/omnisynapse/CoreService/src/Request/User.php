<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class UserCreatedRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string userId
 * @property string username
 * @property string referrerId
 */
class User implements \JsonSerializable
{
    /** @var string */
    public $userId;

    /** @var string */
    public $username;

    /** @var string */
    public $referrerId;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'            => $this->userId,
            'username'      => $this->username,
            'referrer_id'   => $this->referrerId,
        ];
    }

    /**
     * @param string $userId
     * @return User
     */
    public function setUserId(string $userId) : User
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username) : User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param \App\Models\User $referrer
     * @return User
     */
    public function setReferrerId(\App\Models\User $referrer=null) : User
    {
        $this->referrerId = null !== $referrer
            ? $referrer->getId()
            : null;
        return $this;
    }
}