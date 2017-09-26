<?php

namespace App\Models;

use App\Models\NauModels\Account;
use App\Models\Traits\HasAttributes;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Place
 * @package App\Models
 *
 * @property string id
 * @property string user_id
 * @property string name
 * @property string description
 * @property string about
 * @property string address
 * @property float latitude
 * @property float longitude
 * @property int radius
 * @property int stars
 * @property bool is_featured
 * @property string picture_url
 * @property string cover_url
 * @property int offers_count
 *
 * @method static static|Builder byUser(User $user)
 * @method static static|Builder filterByPosition(string $lat = null, string $lng = null, int $radius = null)
 * @method static static|Builder filterByCategories(array $category_ids)
 */
class Place extends Model
{
    use HasAttributes, Uuids;
    /**
     * Place constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'places';
        $this->primaryKey = 'id';

        $this->casts = [
            'id'          => 'string',
            'name'        => 'string',
            'description' => 'string',
            'about'       => 'string',
            'address'     => 'string',
            'latitude'    => 'double',
            'longitude'   => 'double',
            'radius'      => 'integer',
            'stars'       => 'integer',
            'is_featured' => 'boolean'
        ];

        $this->fillable = [
            'name',
            'description',
            'about',
            'address',
            'latitude',
            'longitude',
            'radius'
        ];

        $this->attributes = [
            'name'        => null,
            'description' => null,
            'about'       => null,
            'address'     => null,
            'latitude'    => 0,
            'longitude'   => 0,
            'radius'      => 1
        ];

        $this->appends = [
            'offers_count',
            'picture_url',
            'cover_url'
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return string */
    public function getAbout(): string
    {
        return $this->about;
    }

    /** @return string */
    public function getAddress(): string
    {
        return $this->address;
    }

    /** @return float */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /** @return float */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /** @return int */
    public function getRadius(): int
    {
        return $this->radius;
    }

    /** @return int */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * @return int
     */
    public function getOffersCountAttribute(): int
    {
        return $this->offers()->get()->count();
    }

    public function getOffersAttribute()
    {
        return $this->offers()->get();
    }

    /**
     * @return string
     */
    public function getPictureUrlAttribute(): string
    {
        return route('place.picture.show', ['uuid' => $this->getId(), 'type' => 'picture']);
    }

    /**
     * @return string
     */
    public function getCoverUrlAttribute(): string
    {
        return route('place.picture.show', ['uuid' => $this->getId(), 'type' => 'cover']);
    }



    /** @return bool */
    public function isFeatured(): bool
    {
        return $this->is_featured;
    }

    /**
     * @param string $name
     * @return Place
     */
    public function setName(string $name): Place
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     * @return Place
     */
    public function setDescription(string $description): Place
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $about
     * @return Place
     */
    public function setAbout(string $about): Place
    {
        $this->about = $about;
        return $this;
    }

    /**
     * @param string $address
     * @return Place
     */
    public function setAddress(string $address): Place
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param float $latitude
     * @return Place
     */
    public function setLatitude(float $latitude): Place
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param float $longitude
     * @return Place
     */
    public function setLongitude(float $longitude): Place
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @param int $radius
     * @return Place
     */
    public function setRadius(int $radius): Place
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * @param int $stars
     * @return Place
     */
    public function setStars(int $stars): Place
    {
        $this->stars = $stars;
        return $this;
    }

    /**
     * @param bool $isFeatured
     * @return Place
     */
    public function setIsFeatured(bool $isFeatured): Place
    {
        $this->is_featured = $isFeatured;
        return $this;
    }

    /**
     * @param Builder $builder
     * @param User $user
     * @return Builder
     */
    public function scopeByUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', $user->getId());
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function getOffers()
    {
        return $this->user->getAccountFor(Currency::NAU)->offers()->get();
    }

    /**
     * @return HasMany
     *
     * ATTENTION! it just stub
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Account::class, 'owner_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'places_categories', 'place_id', 'category_id');
    }

    public function offers()
    {
        return $this->user->getAccountFor(Currency::NAU)->offers();
    }

    /**
     * @param Builder $builder
     * @param string|null $lat
     * @param string|null $lng
     * @param int|null $radius
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByPosition(
        Builder $builder,
        string $lat = null,
        string $lng = null,
        int $radius = null
    ): Builder {
        if (empty($lat) || empty($lng) || $radius < 1) {
            return $builder->whereNull('latitude')->whereNull('longitude')->whereNull('radius');
        }

        return $builder->whereRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((latitude - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(latitude * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((longitude - %2$s) * 
        PI()/180 / 2), 2)))) < (radius + %3$d)',
            DB::connection()->getPdo()->quote($lat),
            DB::connection()->getPdo()->quote($lng),
            $radius));
    }

    /**
     * @param Builder $builder
     * @param array $categoryIds
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByCategories(Builder $builder, array $categoryIds): Builder
    {
        return $builder->whereHas('categories', function (Builder $builder) use ($categoryIds) {
            $builder->whereIn('id', $categoryIds);
        });
    }
}
