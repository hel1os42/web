<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class User
 * @package OmniSynapse\CoreService\Response
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class User
{
    /** @var string */
    public $id;

    /** @var string */
    public $username;

    /** @var string|null */
    public $referrer_id = null;

    /** @var int */
    public $level;

    /** @var int */
    public $points;

    /** @var Wallet[] */
    public $wallets;

    /** @var string */
    public $created_at;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getReferrerId(): string
    {
        return $this->referrer_id;
    }

    /**
     * @return Wallet[]
     */
    public function getWallets(): array
    {
        return $this->wallets;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }
}
