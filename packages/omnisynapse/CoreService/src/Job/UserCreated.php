<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
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
     */
    public function __construct(User $user)
    {
        parent::__construct();

        /** @var UserRequest requestObject */
        $this->requestObject = (new UserRequest())
            ->setId($user->getId())
            ->setUsername($user->getName())
            ->setReferrerId($user->getReferrerId());
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

    /**
     * @param Response $response
     */
    public function handleError(Response $response)
    {
        // TODO: handler errors
    }
}