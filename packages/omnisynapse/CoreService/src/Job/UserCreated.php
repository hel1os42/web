<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\User as UserResponse;

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
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(User $user, \GuzzleHttp\Client $client=null)
    {
        $this->client = $client;

        /** @var UserRequest requestObject */
        $this->requestObject = new UserRequest($user);
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
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return UserResponse::class;
    }
}
