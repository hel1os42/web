<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\User;

class UserCreatedRequest extends User implements \JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'referrer_id' => $this->referrer_id,
            'wallets' => $this->wallets,
            'level' => $this->level,
            'points' => $this->points,
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
     * @param string $referrer_id
     * @return UserCreatedRequest
     */
    public function setReferrerId($referrer_id) : UserCreatedRequest
    {
        $this->referrer_id = $referrer_id;
        return $this;
    }
}