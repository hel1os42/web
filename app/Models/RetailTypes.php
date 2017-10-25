<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RetailTypes
 * @package App\Models
 *
 * @property Category category
 */
class RetailTypes extends Model
{
    /**
     * RetailTypes constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'retail_types';
        $this->primaryKey = 'id';

        $this->casts = [
            'name'        => 'string',
            'slug'        => 'string',
        ];

        $this->hidden = [
            'id',
            'category_id',
        ];

        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
