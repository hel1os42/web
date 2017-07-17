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
class UserCreatedRequest implements \JsonSerializable
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
     * @return UserCreatedRequest
     */
    public function setId(string $id) : UserCreatedRequest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $username
     * @return UserCreatedRequest
     */
    public function setUsername(string $username) : UserCreatedRequest
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param UserCreatedRequest $referrer
     * @return UserCreatedRequest
     */
    public function setReferrerId(UserCreatedRequest $referrer=null) : UserCreatedRequest
    {
        $this->referrer_id = null !== $referrer
            ? $referrer->id
            : null;
        return $this;
    }
}