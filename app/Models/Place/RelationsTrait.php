<?php

namespace App\Models\Place;

use App\Models\Category;
use App\Models\Contracts\Currency;
use App\Models\Speciality;
use App\Models\Tag;
use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait RelationsTrait
 *
 * @package App\Models\Place
 */
trait RelationsTrait
{

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     *
     * ATTENTION! it just stub
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'places_categories', 'place_id', 'category_id');
    }

    /**
     * @return HasMany
     *
     * @throws \App\Exceptions\TokenException
     */
    public function offers()
    {
        return $this->user->getAccountFor(Currency::NAU)->offers();
    }

    /**
     * @return BelongsToMany
     */
    public function specialities(): BelongsToMany
    {
        return $this->belongsToMany(Speciality::class, 'places_specialities', 'place_id', 'speciality_id');
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'places_tags', 'place_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function retailTypes()
    {
        return $this->categories()->whereNotNull('parent_id')->orderBy('name');
    }

    /**
     * @return BelongsToMany
     */
    public function category(): BelongsToMany
    {
        return $this->categories()->whereNull('parent_id');
    }
}
