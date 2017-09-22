<?php

namespace App\Models;

use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 *
 */
class Place extends Model
{
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

//        $this->appends = [
//            'offers',
//        ];

        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $model) {
            $model->id = Uuid::generate(4)->__toString();
        });
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

    /** @return bool */
    public function getIsFeatured(): bool
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
     * @param string $uuid
     * @return Builder
     */
    public function scopeFindByUserId(Builder $builder, string $uuid)
    {
        return $builder->where('user_id', $uuid)->first();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
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

}
