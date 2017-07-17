<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;
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

    /**
     * @return array
     */
    public function getWallets() : array
    {
        return $this->wallets;
    }

    /**
     * @return integer
     */
    public function getLevel() : integer
    {
        return $this->level;
    }

    /**
     * @return integer
     */
    public function getPoints() : integer
    {
        return $this->points;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return $this->created_at;
    }
}