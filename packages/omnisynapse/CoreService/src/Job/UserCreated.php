<?php

namespace OmniSynapse\CoreService\Job;

use OmniSynapse\CoreService\Client;
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
     * @param UserCreatedRequest $user
     * @param UserCreatedRequest|null $referrer
     */
    public function __construct(UserCreatedRequest $user, UserCreatedRequest $referrer = null)
    {
        parent::__construct();

        $this->requestObject = (new UserCreatedRequest) // TODO: requestObject ???
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
     * @return UserCreatedResponse
     */
    protected function getResponseClass()
    {
        return new UserCreatedResponse();
    }
}