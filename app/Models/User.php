<?php

namespace App\Models;

use App\Exceptions\TokenException;
use App\Models\Contracts\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\User as CoreUser;
use App\Models\Traits\HasAttributes;
use App\Models\User\RelationsTrait;
use App\Services\Auth\Contracts\PhoneAuthenticable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

/**
 * Class User
 * @package App\Models
 *
 * @property string     id
 * @property string     name
 * @property string     email
 * @property string     password
 * @property string     phone
 * @property string     invite_code
 * @property string     referrer_id
 * @property int        level
 * @property int        points
 * @property Collection offers
 * @property Collection accounts
 * @property CoreUser   coreUser
 * @property User       referrer
 * @property int        offers_count
 * @property int        referrals_count
 * @property int        accounts_count
 * @property int        activation_codes_count
 */
class User extends Authenticatable implements PhoneAuthenticable
{
    use Notifiable, RelationsTrait, HasAttributes;

    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->attributes = [
            'name'           => '',
            'email'          => null,
            'password'       => null,
            'remember_token' => null,
            'created_at'     => null,
            'updated_at'     => null,
            'referrer_id'    => null,
            'invite_code'    => null,
        ];

        $this->casts = [
            'name'      => 'string',
            'email'     => 'string',
            'phone'     => 'string',
            'latitude'  => 'double',
            'longitude' => 'double'
        ];

        $this->fillable = [
            'name',
            'email',
            'password',
            'phone',
            'latitude',
            'longitude'
        ];

        $this->hidden = [
            'coreUser',
            'password',
            'remember_token',
            'referrer_id'
        ];

        $this->appends = [
            'picture_url',
            'level',
            'points',
            'offers_count',
            'referrals_count',
            'accounts_count',
            'activation_codes_count',
        ];

        parent::__construct($attributes);
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function toArray()
    {
        $array = parent::toArray();
        if (array_key_exists('accounts', $array) && count($array['accounts']) > 0) {
            $array['accounts'] = [Currency::NAU => $array['accounts'][0]];
        }

        return $array;
    }

    /**
     * @return User
     */
    public function getReferrer(): ?User
    {
        return $this->referrer;
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
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get user mail
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get user phone
     *
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
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
     * @return string
     */
    public function getPictureUrlAttribute(): string
    {
        return route('users.picture.show', ['id' => $this->getId()]);
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
     * @param null|string $name
     *
     * @return User
     */
    public function setName(?string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set user mail
     *
     * @param null|string $email
     *
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set user phone
     *
     * @param null|string $phone
     *
     * @return User
     */
    public function setPhone(?string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Set user password
     *
     * @param string|null $password
     *
     * @return User
     */
    public function setPassword(?string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param null|string $value
     */
    public function setPasswordAttribute(?string $value)
    {
        if ($value !== null) {
            $this->attributes['password'] = Hash::make($value);
        }
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
     *
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
     * @return User|\Illuminate\Database\Eloquent\Model|null
     * @throws \InvalidArgumentException
     */
    public static function findByInvite(string $invite): ?User
    {
        return self::query()->where('invite_code', $invite)->first();
    }

    /**
     * @param string $phone
     *
     * @return User|\Illuminate\Database\Eloquent\Model|null
     * @throws \InvalidArgumentException
     */
    public static function findByPhone(string $phone): ?User
    {
        return self::query()->where('phone', $phone)->first();
    }

    /**
     * Generate invite when user register
     * @return string
     * @throws \InvalidArgumentException
     */
    public function generateInvite(): string
    {
        $newInvite = substr(uniqid(), 0, rand(3, 8));

        return null !== self::findByInvite($newInvite) ? $this->generateInvite() : $newInvite; // !!DANGEROUS!!
    }

    /**
     * @param string $currency
     *
     * @return Account
     */
    public function getAccountFor(string $currency): ?Account
    {
        switch ($currency) {
            /** @noinspection PhpMissingBreakStatementInspection */
            case Currency::NAU:
                $account = $this->accounts()->first();
                if ($account instanceof Account) {
                    return $account;
                }
                // no break
            default:
                throw new TokenException($currency);
        }
    }

    /**
     * @param User|null $user
     *
     * @return bool
     */
    public function equals(User $user = null)
    {
        return null != $user && $this->id === $user->id;
    }

    /**
     * @return int
     */
    public function getOffersCountAttribute(): int
    {
        return $this->offers()->count();
    }

    /**
     * @return int
     */
    public function getReferralsCountAttribute(): int
    {
        return $this->referrals()->count();
    }

    /**
     * @return int
     */
    public function getAccountsCountAttribute(): int
    {
        return $this->accounts()->count();
    }

    /**
     * @return int
     */
    public function getActivationCodesCountAttribute(): int
    {
        return $this->activationCodes()->count();
    }
}
