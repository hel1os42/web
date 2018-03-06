<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Testimonial
 * @package App\Models
 *
 * @property string  id
 * @property string  text
 * @property string  user_id
 * @property string  offer_id
 * @property integer stars
 * @property string  status
 * @property Carbon  created_at
 * @property Carbon  updated_at
 *
 */
class Testimonial extends Model
{
    use Uuids;

    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_INBOX    = 'inbox';

    /**
     * Testimonial constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'testimonials';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'id'       => 'string',
            'user_id'  => 'string',
            'offer_id' => 'string',
            'text'     => 'string',
            'stars'    => 'integer',
            'status'   => 'string',
        ];

        parent::__construct($attributes);
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getText(): string
    {
        return $this->text;
    }

    /** @return int */
    public function getStars(): int
    {
        return $this->stars;
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
