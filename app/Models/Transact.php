<?php

namespace App\Models;

use App\Models\Traits\HasNau;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property Account sourse
 * @property Account destination
 */
class Transact extends Model
{
    use ReadOnlyTrait;
    use HasNau;

    /** @var string */
    protected $table = "transact";

    /** @var array */
    protected $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    protected $primaryKey = 'txid';

    /** @var array */
    protected $maps = [
        'txid'     => 'id',
        'src_id'   => 'source_account_id',
        'dst_id'   => 'destination_account_id',
    ];

    /** @var array */
    protected $casts = [
        'id'                     => 'string',
        'source_account_id'      => 'string',
        'destination_account_id' => 'string',
        'amount'                 => 'float',
        'status'                 => 'string',
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

    /** @return BelongsTo */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination_account_id', 'id');
    }

    /** @return Carbon */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /** @return Carbon */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }
}