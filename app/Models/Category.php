<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 * @package App
 *
 * @property string id
 * @property string name
 * @property string parent_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Category parent
 * @property Category findByName
 */
class Category extends Model
{
    protected $connection = 'pgsql';

    /**
     * Category constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table      = 'categories';
        $this->primaryKey = 'id';

        $this->casts = [
            'id'        => 'string',
            'name'      => 'string',
            'parent_id' => 'string'
        ];
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return string */
    public function getParentId(): string
    {
        return $this->parent_id;
    }

    /** @return BelongsTo */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function children(): hasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
