<?php

namespace OmniSynapse\CoreService\Failed;

use App\Models\User;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Failed;
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
