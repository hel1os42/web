<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * @package App
 *
 * @property string name
 * @property string email
 * @property string password
 * @property User referrer
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
     * Get the referrer record associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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
        $this->password = Hash::make($password);
        return $this;
    }

    /**
     * Set invite code
     *
     * @param $invite
     * @return $this
     */
    public function setInvite($invite)
    {
        $this->invite_code = $invite;
        return $this;
    }


    /**
     * Find User by invite code
     *
     * @param string $invite
     *
     * @return $this
     */
    public function findByInvite(string $invite)
    {
        return $this->where('invite_code', $invite)->first();
    }


    /**
     * Generate invite when user register
     *
     * @return string
     */
    public function generateInvite()
    {
        $newInvite = substr(uniqid(), 0, rand(3, 8));

        return $this->findByInvite($newInvite) instanceof $this ? $this->generateInvite() : $newInvite;
    }
}
