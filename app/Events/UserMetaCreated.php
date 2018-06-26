<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Should be fired when user and roles records inserted into web_db
 *
 * Class UserMetaCreated
 */
class UserMetaCreated
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * UserMetaCreated constructor.
     *
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(){
        return $this->user;
    }
}
