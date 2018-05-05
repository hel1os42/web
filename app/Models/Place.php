<?php

namespace App\Models;

use App\Helpers\Attributes;
use App\Models\NauModels\Offer;
use App\Models\Place\RelationsTrait;
use App\Models\Place\ScopesTrait;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Prettus\Repository\Traits\PresentableTrait;

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
 * @property string                       alias
 * @property float                        latitude
 * @property float                        longitude
 * @property string                       timezone
 * @property int                          radius
 * @property int                          stars
 * @property bool                         is_featured
 * @property bool                         has_active_offers
 * @property string                       picture_url
 * @property string                       cover_url
 * @property int                          offers_count
 * @property int                          active_offers_count
 * @property bool                         is_favorite
 * @property User                         user
 * @property Collection                   testimonials
 * @property NauModels\Offer[]|Collection offers
 * @property string                       phone
 * @property string                       site
 *
 * @method static static|\Illuminate\Database\Eloquent\Builder byUser(User $user)
 * @method static static|\Illuminate\Database\Eloquent\Builder filterByPosition(string $lat = null, string $lng = null, int $radius = null)
 * @method static static|\Illuminate\Database\Eloquent\Builder filterByCategories(array $categoryIds)
 * @method static static|\Illuminate\Database\Eloquent\Builder filterByActiveOffersAvailability()
 * @method static static|\Illuminate\Database\Eloquent\Builder orderByPosition(string $lat = null, string $lng = null)
 * @method static static|\Illuminate\Database\Eloquent\Builder byAlias(String $alias)
 */
class Place extends Model
{
    use Uuids, RelationsTrait, PresentableTrait, ScopesTrait;

    /**
     * Place constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
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
            'alias'             => 'string',
            'latitude'          => 'double',
            'longitude'         => 'double',
            'radius'            => 'integer',
            'stars'             => 'integer',
            'is_featured'       => 'boolean',
            'has_active_offers' => 'boolean',
            'phone'             => 'string',
            'site'              => 'string',
        ];

        $this->hidden = [
            'user'
        ];

        $this->fillable = [
            'name',
            'description',
            'about',
            'address',
            'alias',
            'latitude',
            'longitude',
            'radius',
            'phone',
            'site',
            'timezone',
        ];

        $this->attributes = [
            'name'        => null,
            'description' => null,
            'about'       => null,
            'address'     => null,
            'alias'       => null,
            'latitude'    => 0,
            'longitude'   => 0,
            'radius'      => 1,
            'phone'       => null,
            'site'        => null,
        ];

        $this->appends = [
            'categories_count',
            'testimonials_count',
            'offers_count',
            'active_offers_count',
            'picture_url',
            'cover_url',
            'timezone_offset',
            'redemptions_count'
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

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return null|string
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @return null|string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return null|string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
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
     * @return string
     */
    public function getTimezoneOffsetAttribute(): string
    {
        $utcTimezone = new \DateTimeZone('UTC');
        $currentDate = new \DateTime('now', $utcTimezone);

        try {
            $timezone = new \DateTimeZone($this->timezone);
        } catch (\Exception $exception) {
            $timezone = $utcTimezone;
        }

        return $timezone->getOffset($currentDate);
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
     * @return int
     * @throws \App\Exceptions\TokenException
     */
    public function getRedemptionsCountAttribute(): int
    {
        return $this->offers()->get()->sum(function (Offer $item) {
            return $item->getRedemptionsCount();
        });
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
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsFavoriteAttribute(): bool
    {
        return $this->attributes['is_favorite'] ?? false;
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
     * @param string $alias
     *
     * @return Place
     */
    public function setAlias(string $alias): Place
    {
        $this->alias = $alias;

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

    public function setIsFavoriteAttribute($isFavorite)
    {
        $this->attributes['is_favorite'] = $isFavorite;

        return $this;
    }

    /**
     * @return array
     */
    public function getFillableWithDefaults(): array
    {
        return Attributes::getFillableWithDefaults($this);
    }
}
