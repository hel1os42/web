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
     */
    public function __construct(UserCreatedRequest $user)
    {
        parent::__construct();

        /** @var UserCreatedRequest requestObject */
        $this->requestObject = $user;
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