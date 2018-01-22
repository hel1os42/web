<?php

namespace App\Models;

use App\Helpers\Attributes;
use App\Models\Contracts\Currency;
use App\Models\NauModels\Offer;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class Place
 * @package App\Models
 *
 * @property string                       id
 * @property string                       user_id
 * @property string                       name
 * @property string                       description
 * @property string                       about
 * @property string                       address
 * @property float                        latitude
 * @property float                        longitude
 * @property int                          radius
 * @property int                          stars
 * @property bool                         is_featured
 * @property bool                         has_active_offers
 * @property string                       picture_url
 * @property string                       cover_url
 * @property int                          offers_count
 * @property int                          active_offers_count
 *
 * @property User                         user
 * @property Collection                   testimonials
 * @property NauModels\Offer[]|Collection offers
 *
 * @method static static|Builder byUser(User $user)
 * @method static static|Builder filterByPosition(string $lat = null, string $lng = null, int $radius = null)
 * @method static static|Builder filterByCategories(array $categoryIds)
 * @method static static|Builder filterByActiveOffersAvailability()
 * @method static static|Builder orderByPosition(string $lat = null, string $lng = null)
 */
class Place extends Model
{
    use Uuids;

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

        $this->initUuid();

        $this->casts = [
            'id'                => 'string',
            'name'              => 'string',
            'description'       => 'string',
            'about'             => 'string',
            'address'           => 'string',
            'latitude'          => 'double',
            'longitude'         => 'double',
            'radius'            => 'integer',
            'stars'             => 'integer',
            'is_featured'       => 'boolean',
            'has_active_offers' => 'boolean',
        ];

        $this->hidden = [
            'user'
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
            'categories_count',
            'testimonials_count',
            'offers_count',
            'active_offers_count',
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

    public function getActiveOffersCountAttribute(): int
    {
        return $this->offers()->count();
    }

    /**
     * @return int
     * @throws \App\Exceptions\TokenException
     */
    public function getOffersCountAttribute(): int
    {
        return $this->offers()->withoutGlobalScopes([Offer::statusActiveScope(), Offer::dateActualScope()])->count();
    }

    /**
     * @return int
     */
    public function getTestimonialsCountAttribute(): int
    {
        return $this->testimonials()->count();
    }

    /**
     * @return int
     */
    public function getCategoriesCountAttribute(): int
    {
        return $this->categories()->count();
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
        return route('places.picture.show', ['uuid' => $this->getId(), 'type' => 'picture']);
    }

    /**
     * @return string
     */
    public function getCoverUrlAttribute(): string
    {
        return route('places.picture.show', ['uuid' => $this->getId(), 'type' => 'cover']);
    }

    /**
     * @return string
     */
    public function getOwnOrDefaultCoverUrl(): string
    {
        if (file_exists($this->getCoverPath())) {
            return route('places.picture.show', ['uuid' => $this->getId(), 'type' => 'cover']);
        }

        return self::getDefaultCoverUrl();
    }

    /**
     * @return string
     */
    public function getCoverPath(): string
    {
        return storage_path(sprintf('app/images/place/covers/%1$s.jpg', $this->getKey()));
    }

    /**
     * @return string
     */
    public static function getDefaultCoverUrl(): string
    {
        return asset('/img/default_place_cover.jpg');
    }

    /**
     * @return bool
     */
    public function hasActiveOffers(): bool
    {
        return $this->has_active_offers;
    }

    /** @return bool */
    public function isFeatured(): bool
    {
        return $this->is_featured;
    }

    /**
     * @param string $name
     *
     * @return Place
     */
    public function setName(string $name): Place
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return Place
     */
    public function setDescription(string $description): Place
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $about
     *
     * @return Place
     */
    public function setAbout(string $about): Place
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return Place
     */
    public function setAddress(string $address): Place
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param float $latitude
     *
     * @return Place
     */
    public function setLatitude(float $latitude): Place
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @param float $longitude
     *
     * @return Place
     */
    public function setLongitude(float $longitude): Place
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @param int $radius
     *
     * @return Place
     */
    public function setRadius(int $radius): Place
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * @param int $stars
     *
     * @return Place
     */
    public function setStars(int $stars): Place
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * @param bool $isFeatured
     *
     * @return Place
     */
    public function setIsFeatured(bool $isFeatured): Place
    {
        $this->is_featured = $isFeatured;

        return $this;
    }

    /**
     * @param bool $hasActiveOffers
     *
     * @return Place
     */
    public function setHasActiveOffers(bool $hasActiveOffers): Place
    {
        $this->has_active_offers = $hasActiveOffers;

        return $this;
    }

    /**
     * @param Builder $builder
     * @param User    $user
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByUser($builder, User $user): Builder
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
            $this->getConnection()->getPdo()->quote($lat),
            $this->getConnection()->getPdo()->quote($lng),
            $radius));
    }

    /**
     * @param Builder     $builder
     * @param string|null $lat
     * @param string|null $lng
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeOrderByPosition(Builder $builder, string $lat = null, string $lng = null): Builder
    {
        if (isset($lat, $lng)) {
            return $builder->orderByRaw(sprintf('(6371000 * 2 * 
        ASIN(SQRT(POWER(SIN((latitude - ABS(%1$s)) * 
        PI()/180 / 2), 2) + COS(latitude * PI()/180) * 
        COS(ABS(%1$s) * PI()/180) * 
        POWER(SIN((longitude - %2$s) * 
        PI()/180 / 2), 2))))',
                $this->getConnection()->getPdo()->quote($lat),
                $this->getConnection()->getPdo()->quote($lng)));
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array   $categoryIds
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByCategories(Builder $builder, array $categoryIds): Builder
    {
        return $builder->whereHas('categories', function (Builder $builder) use ($categoryIds) {
            $builder->whereIn('id', $categoryIds)->orWhereIn('parent_id', $categoryIds);
        });
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeFilterByActiveOffersAvailability(Builder $builder): Builder
    {
        return $builder->where('has_active_offers', '=', true);
    }


    /**
     * @return array
     */
    public function getFillableWithDefaults(): array
    {
        return Attributes::getFillableWithDefaults($this);
    }
}
