<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Complaint
 * @package App\Models
 *
 * @property string      id
 * @property string|null text
 * @property string      user_id
 * @property string      place_id
 * @property string      status
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property User        user
 * @property Place       place
 */
class Complaint extends Model
{
    use Uuids;

    const STATUS_INBOX = 'inbox';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';

    /**
     * Complaint constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'complaints';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'id'       => 'string',
            'user_id'  => 'string',
            'place_id' => 'string',
            'text'     => 'string',
            'status'   => 'string',
        ];

        $this->fillable = [
            'user_id',
            'place_id',
            'text',
            'status',
        ];

        parent::__construct($attributes);
    }

    /**
     * @return array
     */
    public static function getAllStatuses()
    {
        return [
            self::STATUS_INBOX,
            self::STATUS_SENDING,
            self::STATUS_SENT,
        ];
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
