<?php

namespace App\Models;

use App\Exceptions\TokenException;
use App\Helpers\Attributes;
use App\Models\Contracts\Currency;
use App\Models\NauModels\Account;
use App\Models\NauModels\User as CoreUser;
use App\Models\User\EnrollmentTrait;
use App\Models\User\RelationsTrait;
use App\Models\User\RoleTrait;
use App\Services\Auth\Contracts\PhoneAuthenticable;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;
use OmniSynapse\WebHookService\Traits\HasWebHooks;

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
 * @property bool       approved
 * @property int        referral_points
 * @property int        redemption_points
 * @property Collection offers
 * @property Collection accounts
 * @property Collection roles
 * @property Collection parents
 * @property Collection children
 * @property CoreUser   coreUser
 * @property User       referrer
 * @property int        offers_count
 * @property int        referrals_count
 * @property int        accounts_count
 * @property int        activation_codes_count
 * @property Place      place
 * @property string     picture_url
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany offers
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany roles
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany parents
 * @method \Illuminate\Database\Eloquent\Relations\BelongsToMany children
 */
class User extends Authenticatable implements PhoneAuthenticable
{

    use Notifiable, RelationsTrait, RoleTrait, Impersonate, Uuids, EnrollmentTrait, HasWebHooks;

    const PROFILE_PICTURES_PATH = 'images/profile/pictures';

    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->attributes = [
            'name'              => '',
            'email'             => null,
            'password'          => null,
            'remember_token'    => null,
            'created_at'        => null,
            'updated_at'        => null,
            'referrer_id'       => null,
            'invite_code'       => null,
            'approved'          => false,
            'referral_points'   => 0,
            'redemption_points' => 0,
        ];

        $this->casts = [
            'name'              => 'string',
            'email'             => 'string',
            'phone'             => 'string',
            'latitude'          => 'double',
            'longitude'         => 'double',
            'approved'          => 'boolean',
            'invite_code'       => 'string',
            'referral_points'   => 'integer',
            'redemption_points' => 'integer',
            'confirmed'         => 'boolean',
        ];

        $this->fillable = [
            'name',
            'email',
            'password',
            'phone',
            'latitude',
            'longitude',
            'approved',
            'invite_code',
            'confirmed',
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
     * @return int
     */
    public function getReferralPoints(): int
    {
        return $this->referral_points;
    }

    /**
     * @return int
     */
    public function getRedemptionPoints(): int
    {
        return $this->redemption_points;
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
    public function getPointsAttribute(): int
    {
        return $this->coreUser->points ?? 0;
    }

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
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

    /**
     * @param int $points
     *
     * @return User
     */
    public function setReferralPoints(int $points): User
    {
        $this->referral_points = $points;

        return $this;
    }

    /**
     * @param int $points
     *
     * @return User
     */
    public function setRedemptionPoints(int $points): User
    {
        $this->redemption_points = $points;

        return $this;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $model) {
            if (null === $model->invite_code) {
                $model->invite_code = $model->generateInvite();
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
     * @param bool $approve
     *
     * @return User
     */
    public function setApproved(bool $approve): User
    {
        $this->approved = $approve;

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
        $newInvite = substr(md5(rand()), 0, 5);

        return null !== self::findByInvite($newInvite) ? $this->generateInvite() : $newInvite; // !!DANGEROUS!!
    }

    /**
     * @param string $currency
     *
     * @return Account|null
     * @throws TokenException
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
     * @return Account|null
     * @throws TokenException
     */
    public function getAccountForNau(): ?Account
    {
        return $this->getAccountFor(Currency::NAU);
    }

    /**
     * @param User|null $user
     *
     * @return bool
     */
    public function equals(User $user = null): bool
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


    /**
     * @param User $parent
     *
     * @return mixed
     */
    public function hasParent(User $parent)
    {
        return $this->parents->contains($parent->getId());
    }

    /**
     * @param User $child
     *
     * @return bool
     */
    public function hasChild(User $child)
    {
        return $this->children->contains($child->getId());
    }

    /**
     * @return bool
     */
    public function isImpersonated(): bool
    {
        $keyName = config('laravel-impersonate.session_key');

        if (\Tymon\JWTAuth\Facades\JWTAuth::getToken() !== false) {
            $payload = \Tymon\JWTAuth\Facades\JWTAuth::getPayload();

            return $payload->get($keyName) !== false;
        }

        return session()->has($keyName) !== false;

    }

    /**
     * @param array $without
     *
     * @return array
     */
    public function getFillableWithDefaults($without = []): array
    {
        return Attributes::getFillableWithDefaults($this, $without);
    }

    /**
     * @param string $uuid User ID
     * @return string
     */
    public static function getUserAvatarPath(string $uuid)
    {
        return storage_path(sprintf('app/%1$s/%2$s.%3$s', self::PROFILE_PICTURES_PATH, $uuid, 'jpg'));
    }
}
