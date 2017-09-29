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
 * @property string id
 * @property string text
 * @property string user_id
 * @property string offer_id
 * @property integer stars
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class Testimonial extends Model
{
    use Uuids;

    /**
     * Testimonial constructor.
     *
     * @param array $attributes
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
            'stars'    => 'integer'
        ];

        $this->hidden = [
            'created_at',
            'updated_at',
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

    /** @return string */
    public function getStars(): string
    {
        return $this->stars;
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
