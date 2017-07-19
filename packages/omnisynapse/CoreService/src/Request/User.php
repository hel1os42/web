<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class UserCreatedRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @property string id
 * @property string username
 * @property string referrer_id
 */
class User implements \JsonSerializable
{
    /** @var string */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $referrer_id;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'referrer_id' => $this->referrer_id,
        ];
    }

    /**
     * @param string $id
     * @return User
     */
    public function setId(string $id) : User
    {
        $this->id = $id;
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
     * @param User $referrer
     * @return User
     */
    public function setReferrerId(User $referrer=null) : User
    {
        $this->referrer_id = null !== $referrer
            ? $referrer->id
            : null;
        return $this;
    }
}