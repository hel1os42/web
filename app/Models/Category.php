<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Category
 * @package App\Models
 *
 * @property string   id
 * @property string   name
 * @property string   parent_id
 * @property Carbon   created_at
 * @property Carbon   updated_at
 * @property Category parent
 *
 * @method static Category[]|Collection|Builder withParent(Category $parent)
 * @method static Category[]|Collection|Builder withNoParent()
 */
class Category extends Model
{
    use Uuids;
    /**
     * Category constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'categories';
        $this->primaryKey = 'id';

        $this->initUUID();

        $this->casts = [
            'id'        => 'string',
            'name'      => 'string',
            'parent_id' => 'string'
        ];

        $this->hidden = [
            'created_at',
            'updated_at',
        ];

        $this->appends = [
            'children_count',
        ];

        parent::__construct($attributes);
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

    /**
     * @return int
     */
    public function getChildrenCountAttribute(): int
    {
        return $this->children()->count();
    }

    public function scopeWithParent(Builder $builder, Category $parent)
    {
        return $builder->where('parent_id', $parent->getId());
    }

    public function scopeWithNoParent(Builder $builder)
    {
        return $builder->whereNull('parent_id');
    }
}
