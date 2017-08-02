<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\User as UserResponse;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends AbstractJob
{
    /** @var UserRequest */
    private $requestObject;

    /**
     * UserCreated constructor.
     *
     * @param User $user
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(User $user, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        /** @var UserRequest requestObject */
        $this->requestObject = new UserRequest($user);
    }

    /**
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * @return string
     */
    protected function getHttpPath(): string
    {
        return '/users';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass(): string
    {
        return UserResponse::class;
    }
}
