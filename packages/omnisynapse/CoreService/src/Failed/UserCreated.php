<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\User;
use OmniSynapse\CoreServise\Failed\Failed;

/**
 * Class UserCreatedFailed
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends Failed
{
    /** @var User */
    private $user;

    /**
     * @param \Exception $exception
     * @param User|null $user
     */
    public function __construct(\Exception $exception, User $user = null)
    {
        parent::__construct($exception);
        $this->user = $user;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
