<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Role;

/**
 * Class UserCreatedRequest
 * @package OmniSynapse\CoreService\Request
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class User implements \JsonSerializable
{
    /** @var string */
    public $userId;

    /** @var string */
    public $username;

    /** @var string */
    public $referrerId;

    /** @var null|string */
    public $defaultRole = null;

    /**
     * User constructor.
     *
     * @param \App\Models\User $user
     */
    public function __construct(\App\Models\User $user)
    {
        $this->setUserId($user->getId())
             ->setUsername($user->getName())
             ->setReferrerId($user->getReferrer())
             ->identifyDefaultRole($user);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'           => $this->userId,
            'username'     => $this->username,
            'referrer_id'  => $this->referrerId,
            'default_role' => $this->defaultRole,
        ];
    }

    /**
     * @param string $userId
     *
     * @return User
     */
    public function setUserId(string $userId): User
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param \App\Models\User $referrer
     *
     * @return User
     */
    public function setReferrerId(\App\Models\User $referrer = null): User
    {
        $this->referrerId = null !== $referrer
            ? $referrer->getId()
            : null;

        return $this;
    }

    /**
     * Detects default role
     *
     * @param \App\Models\User $user
     *
     * @return null|string
     */
    private function identifyDefaultRole(\App\Models\User $user): ?string
    {
        $defaultRole = null;

        $user->load('roles');

        if ($user->isAdmin()) {
            $defaultRole = Role::ROLE_ADMIN;
        } elseif ($user->isAgent()) {
            $defaultRole = Role::ROLE_AGENT;
        } elseif ($user->isAdvertiser() && null === $user->getPhone() && null !== $user->getEmail()) {
            $defaultRole = Role::ROLE_ADVERTISER;
        } elseif ($user->isUser() && null !== $user->getPhone()) {
            $defaultRole = Role::ROLE_USER;
        }

        return $this->defaultRole = $defaultRole;
    }
}
