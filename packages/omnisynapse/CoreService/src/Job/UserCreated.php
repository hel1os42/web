<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Entity\User;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\UserCreatedRequest;
use \OmniSynapse\CoreService\Response\UserCreatedResponse;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends Job
{
    /**
     * UserCreated constructor.
     *
     * @param User $user
     * @param User|null $referrer
     */
    public function __construct(User $user, User $referrer = null)
    {
        parent::__construct();

        /** @var UserCreatedRequest requestObject */
        $this->requestObject = (new UserCreatedRequest)
            ->setId($user->id)
            ->setUsername($user->username)
            ->setReferrerId(null !== $referrer ? $referrer->id : null);
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_PUT;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/user';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return new UserCreatedRequest();
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return UserCreatedResponse::class;
    }
}