<?php

namespace App\Models;

use App\Models\NauModels\Account;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\User as CoreUser;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use PDepend\Source\Parser\TokenException;
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
 * @property string referrer_id
 * @property int level
 * @property int points
 * @property Account account
 * @property CoreUser coreUser
 * @property User referrer
 */
class User extends Authenticatable
{

    use Notifiable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('database.default');

        $this->fillable = [
            'name',
            'email',
            'password',
        ];

        $this->hidden = [
            'coreUser',
            'password',
            'remember_token',
            'referrer_id'
        ];

        $this->appends = [
            'level',
            'points'
        ];

    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the referrer record associated with the user.
     *
     * @return Relations\BelongsTo
     */
    public function referrer(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User
     */
    public function getReferrer(): User
    {
        return $this->referrer;
    }

    /**
     * Get the user accounts relation
     *
     * @return Relations\HasMany
     */
    public function account(): Relations\HasMany
    {
        return $this->hasMany(Account::class, 'owner_id', 'id');
    }

    /**
     * @return Relations\HasMany
     */
    public function activationCodes(): Relations\HasMany
    {
        return $this->hasMany(ActivationCode::class);
    }

    /**
     * @return Relations\HasOne
     */
    public function coreUser(): Relations\HasOne
    {
        return $this->hasOne(CoreUser::class, 'id', 'id');
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get user mail
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get user referrer id
     *
     * @return string
     */
    public function getReferrerId(): string
    {
        return $this->referrer_id;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getLevelAttribute(): int
    {
        return $this->coreUser->level ?? 0;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getPointsAttribute(): int
    {
        return $this->coreUser->points ?? 0;
    }

    /**
     * Set user name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set user mail
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set user password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $value
     */
    public function setPasswordAttribute(string $value)
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
     * @param string $invite
     * @return User
     */
    public function setInvite(string $invite): User
    {
        $this->invite_code = $invite;

        return $this;
    }

    /**
     * Find User by invite code
     *
     * @param string $invite
     *
     * @return User|null
     * @throws \InvalidArgumentException
     */
    public function findByInvite(string $invite): ?User
    {
        return $this->where('invite_code', $invite)->first();
    }

    /**
     * Generate invite when user register
     * @return string
     * @throws \InvalidArgumentException
     */
    public function generateInvite(): string
    {
        $newInvite = substr(uniqid(), 0, rand(3, 8));

        return $this->findByInvite($newInvite) instanceof $this ? $this->generateInvite() : $newInvite;
    }

    /**
     * @param string $currency
     * @return Account
     */
    public function getAccountFor(string $currency): ?Account
    {
        switch ($currency) {
            case Currency::NAU:
                $account = $this->account()->first();
                if($account){
                    return $account;
                }
                throw new TokenException("no account " . $currency);
            default:
                throw new TokenException("unknown token " . $currency);
        }
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function equals(User $user = null)
    {
        return null != $user && $this->id === $user->id;
    }
}
