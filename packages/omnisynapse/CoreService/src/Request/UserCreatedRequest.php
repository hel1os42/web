<?php

namespace OmniSynapse\CoreService\Request;

use OmniSynapse\CoreService\Entity\User;

class UserCreatedRequest extends User implements \JsonSerializable
{
    public function jsonSerialize()
    {

    }

    /**
     * @param string $id
     * @return UserCreatedRequest
     */
    public function setId($id) : UserCreatedRequest
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $username
     * @return UserCreatedRequest
     */
    public function setUsername($username) : UserCreatedRequest
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