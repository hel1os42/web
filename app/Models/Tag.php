<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Tags
 * @package App\Models
 *
 * @property Category category
 * @property int      id
 */
class Tag extends Model
{
    /**
     * Tags constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'tags';
        $this->primaryKey = 'id';

        $this->casts = [
            'id'   => 'integer',
            'name' => 'string',
            'slug' => 'string',
        ];

        $this->fillable = [
            'name',
            'slug',
            'category_id'
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
