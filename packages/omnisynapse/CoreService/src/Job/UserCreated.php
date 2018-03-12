<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\BaseResponse;
use OmniSynapse\CoreService\Response\User as UserResponse;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends AbstractJob
{
    /** @var null|UserRequest */
    public $requestObject;

    /** @var User */
    private $user;

    /**
     * UserCreated constructor.
     *
     * @param User $user
     * @param CoreService $coreService
     */
    public function __construct(User $user, CoreService $coreService)
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
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return $this->requestObject;
    }

    /** @return BaseResponse */
    public function getResponseObject(): BaseResponse
    {
        return new UserResponse;
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\UserCreated($exception, $this->user);
    }
}
