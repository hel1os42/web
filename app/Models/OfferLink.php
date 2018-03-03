<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferLink extends Model
{
    protected $fillable = [
        'user_id',
        'tag',
        'title',
        'description',
    ];

    protected $casts = [
        'user_id'     => 'string',
        'tag'         => 'string',
        'title'       => 'string',
        'description' => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
