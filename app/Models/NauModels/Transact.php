<?php

namespace App\Models\NauModels;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Transact
 * @package App
 *
 * @property string id
 * @property int source_account_id
 * @property int destination_account_id
 * @property float amount
 * @property string status
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Account source
 * @property Account destination
 * @property string type
 * @method static Transact forAccount(Account $account)
 * @method static Transact forUser(User $user)
 */
class Transact extends NauModel
{
    const TYPE_REDEMPTION = 'redemption';
    const TYPE_P2P        = 'p2p';
    const TYPE_INCOMING   = 'incoming';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = "transact";

        $this->primaryKey = 'txid';

        $this->casts = [
            'txid'    => 'string',
            'src_id'  => 'string',
            'dst_id'  => 'string',
            'amount'  => 'float',
            'status'  => 'string',
            'tx_type' => 'string',
        ];

        $this->appends = [
            'id',
            'source_account_id',
            'destination_account_id',
            'type',
        ];

        $this->hidden = [
            'txid',
            'src_id',
            'dst_id',
            'tx_type',
        ];
    }

    /** @var array */
    protected $maps = [
        'id'                     => 'txid',
        'source_account_id'      => 'src_id',
        'destination_account_id' => 'dst_id',
        'tx_type'                => 'type'
    ];

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getSourceAccountId(): string
    {
        return $this->source_account_id;
    }

    /** @return string */
    public function getDestinationAccountId(): string
    {
        return $this->destination_account_id;
    }

    /**
     * @param int $value
     * @return float
     */
    public function getAmountAttribute(int $value): float
    {
        return $this->convertIntToFloat($value);
    }

    /** @return float */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /** @return BelongsTo */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'source_account_id', 'id');
    }

    /**
     * @return Account
     */
    public function getSource() : Account
    {
        return $this->source;
    }

    /** @return BelongsTo */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination_account_id', 'id');
    }

    /**
     * @return Account
     */
    public function getDestination(): Account
    {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isTypeRedemption(): bool
    {
        return $this->getType() === self::TYPE_REDEMPTION;
    }

    /**
     * @return bool
     */
    public function isTypeP2p(): bool
    {
        return $this->getType() === self::TYPE_P2P;
    }

    /**
     * @return bool
     */
    public function isTypeIncoming(): bool
    {
        return $this->getType() === self::TYPE_INCOMING;
    }

    /**
     * @param Builder $query
     * @param Account $account
     * @return Builder
     */
    public function scopeForAccount(Builder $query, Account $account): Builder
    {
        return $query->where('source_account_id', $account->getId())
            ->orWhere('destination_account_id', $account->getId());
    }

    /**
     * @param Builder $query
     * @param \App\Models\User $user
     * @return Builder
     */
    public function scopeForUser(Builder $query, \App\Models\User $user): Builder
    {
        $accountIds = $user->account()
            ->pluck('id');

        return $query->whereIn('source_account_id', $accountIds)
            ->orWhereIn('destination_account_id', $accountIds);
    }
}
