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
 * @property string txid
 * @property int src_id
 * @property int dst_id
 * @property float amount
 * @property string status
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Transact extends Model
{
    use ReadOnlyTrait;
    use HasNau;

    /** @var string */
    private $table = "transact";

    /** @var array */
    private $timestamps = ['created_at', 'updated_at'];

    /** @var string */
    private $primaryKey = 'txid';

    /** @var array */
    protected $casts = [
        'txid'   => 'string',
        'src_id' => 'string',
        'dst_id' => 'string',
        'amount' => 'float',
        'status' => 'string',
    ];

    /** @return string */
    public function getTxId(): string
    {
        return $this->txid;
    }

    /** @return string */
    public function getSrcId(): string
    {
        return $this->src_id;
    }

    /** @return string */
    public function getDstId(): string
    {
        return $this->dst_id;
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

    /** @return Carbon */
    public function getCreatedAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /** @return Carbon */
    public function getUpdatedAt(): Carbon
    {
        return Carbon::parse($this->updated_at);
    }

    /** @return BelongsTo */
    public function src_id(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'src_id', 'id');
    }

    /** @return BelongsTo */
    public function dst_id(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'dst_id', 'id');
    }
}