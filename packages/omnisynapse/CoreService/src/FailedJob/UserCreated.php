<?php

namespace OmniSynapse\CoreService\FailedJob;

use App\Models\User;
use OmniSynapse\CoreService\FailedJob;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\FailedJob
 */
class UserCreated extends FailedJob
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
