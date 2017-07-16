<?php

namespace OmniSynapse\CoreService\Response;

use OmniSynapse\CoreService\Entity\User;

class UserCreatedResponse extends User
{
    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getReferrerId() : string
    {
        return $this->referrer_id;
    }
}