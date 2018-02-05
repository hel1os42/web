<?php

namespace App\Models\NauModels;

use App\Exceptions\TokenException;
use App\Models\Contracts\Currency;
use App\Models\User as WebUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class User
 * @package App\Models\NauModels
 *
 * @property string  id
 * @property int     level
 * @property int     points
 * @property WebUser owner
 */
class User extends AbstractNauModel
{
    const MASTER_USER_UUID         = '00000000-0000-0000-0000-000000000000';
    const RESERVE_USER_UUID        = '00000000-0000-0000-0000-100000000000';
    const ADVERT_REWARD_USER_UUID  = '00000000-0000-0000-0000-200000000000';
    const USER_REWARD_USER_UUID    = '00000000-0000-0000-0000-300000000000';
    const ADVISER_HOLD_USER_UUID   = '00000000-0000-0000-0000-400000000000';
    const BOUNTY_REWARD_USER_UUID  = '00000000-0000-0000-0000-500000000000';
    const REDEMPTION_FEE_USER_UUID = '00000000-0000-0000-0000-600000000000';
    const P2P_FEE_USER_UUID        = '00000000-0000-0000-0000-700000000000';
    const CROSS_CHANGE_USER_UUID   = '00000000-0000-0000-0000-800000000000';

    /**
     * User constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->table = 'users';

        $this->primaryKey = 'id';

        $this->appends = ['points'];

        $this->setVisible([
            'level',
            'points'
        ]);

        parent::__construct($attributes);
    }

    /** @return BelongsTo */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'id', 'id');
    }

    /** @return int */
    public function getLevel(): int
    {
        return $this->level;
    }

    /** @return int */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getPointsAttribute(): int
    {
        return $this->getLevel() * 100;
    }

    /**
     * Get the user accounts relation
     *
     * @return HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'owner_id', 'id');
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
     * @return array|null
     */
    public static function getSpecialUsersArray(): ?array
    {
        return [
            self::MASTER_USER_UUID         => 'Master User',
            self::RESERVE_USER_UUID        => 'Reserve User',
            self::ADVERT_REWARD_USER_UUID  => 'Advert Reward User',
            self::USER_REWARD_USER_UUID    => 'User Reward User',
            self::ADVISER_HOLD_USER_UUID   => 'Adviser Hold User',
            self::BOUNTY_REWARD_USER_UUID  => 'Bounty Reward User',
            self::REDEMPTION_FEE_USER_UUID => 'Redemption Fee User',
            self::P2P_FEE_USER_UUID        => 'P2P Fee User',
            self::CROSS_CHANGE_USER_UUID   => 'CrossChange User'
        ];
    }
}
