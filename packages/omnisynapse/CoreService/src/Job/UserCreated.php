<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreServiceImpl;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\User as UserResponse;
use OmniSynapse\CoreService\Failed\Failed;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends AbstractJob
{
    /** @var UserRequest */
    public $requestObject;

    /** @var User */
    private $user;

    /**
     * UserCreated constructor.
     *
     * @param User $user
     * @param CoreServiceImpl $coreService
     */
    public function __construct(User $user, CoreServiceImpl $coreService)
    {
        parent::__construct($coreService);

        $this->user = $user;

        /** @var UserRequest requestObject */
        $this->requestObject = new UserRequest($user);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['requestObject', 'user']);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/users';
    }

    /**
     * @return \JsonSerializable
     */
    public function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return UserResponse::class;
    }

    /**
     * @param \Exception $exception
     * @return Failed
     */
    protected function getFailedResponseObject(\Exception $exception): Failed
    {
        return new \OmniSynapse\CoreService\Failed\UserCreated($exception, $this->user);
    }
}
