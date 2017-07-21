<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

/**
 * Class User
 * @package App
 *
 * @property string id
 * @property string name
 * @property string email
 * @property string password
 * @property string invite_code
 * @property mixed  referrer_id
 *
 * @property User   referrer
 */
class User extends Authenticatable
{

    use Notifiable;

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
        return $this->belongsTo(User::class);
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
        $this->password = $password;

        return $this;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $model) {
            if (null === $model->invite_code) {
                $model->invite_code = $model->generateInvite();
                $model->id          = Uuid::generate(4)->__toString();
            }
        });
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
     * @throws \InvalidArgumentException
     */
    public function findByInvite(string $invite)
    {
        return $this->where('invite_code', $invite)->first();
    }

    /**
     * Generate invite when user register
     * @return string
     * @throws \InvalidArgumentException
     */
    public function generateInvite()
    {
        $newInvite = substr(uniqid(), 0, rand(3, 8));

        return $this->findByInvite($newInvite) instanceof $this ? $this->generateInvite() : $newInvite;
    }
}
