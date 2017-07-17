<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 *
 * @property string name
 * @property string email
 * @property string password
 */
class User extends Authenticatable
{

    use Notifiable;
    use \App\Traits\Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'referrer_id'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * Get user name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user mail
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Get user referrer id
     *
     * @return mixed
     */
    public function getReferrerId()
    {
        return $this->referrer_id;
    }


    /**
     * Set user name
     *
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set user mail
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set user password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set user referrer id
     *
     * @param string $referrerId
     *
     * @return $this
     */
    public function setReferrerId($referrerId)
    {
        $this->referrer_id = $referrerId;
    }

}
