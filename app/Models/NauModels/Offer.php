<?php

namespace App\Models\NauModels;

use App\Exceptions\Offer\Redemption\BadActivationCodeException;
use App\Exceptions\Offer\Redemption\CannotRedeemException;
use App\Models\NauModels\Offer\HasOfferData;
use App\Models\NauModels\Offer\RelationsTrait;
use App\Models\NauModels\Offer\ScopesTrait;
use App\Models\OfferLink;
use App\Models\Traits\HasNau;
use App\Models\User;
use App\Traits\Uuids;
use Carbon\Carbon;
use app\Observers\OfferObserver;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Prettus\Repository\Traits\PresentableTrait;

/**
 * Class Offer
 * @package App\Models\NauModels
 *
 * @property string      id
 * @property int         account_id
 * @property string      label
 * @property string      description
 * @property float       reward
 * @property string      status
 * @property Carbon      start_date
 * @property null|Carbon finish_date
 * @property null|string country
 * @property null|string city
 * @property null|string category_id
 * @property null|int    max_count
 * @property null|int    max_for_user
 * @property null|int    max_per_day
 * @property null|int    max_for_user_per_day
 * @property null|int    max_for_user_per_week
 * @property null|int    max_for_user_per_month
 * @property int         user_level_min
 * @property null|float  latitude
 * @property null|float  longitude
 * @property null|int    radius
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property boolean     delivery
 * @property null|string type
 * @property null|string gift_bonus_descr
 * @property null|float  discount_percent
 * @property null|float  discount_start_price
 * @property null|float  discount_finish_price
 * @property null|string currency
 * @property bool        is_favorite
 * @property int         redemptions_count
 * @property int         referral_points_price
 * @property int         redemption_points_price
 * @property bool        is_featured
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Offer extends AbstractNauModel
{
    use RelationsTrait, ScopesTrait, HasNau, Uuids, SoftDeletes, HasOfferData, PresentableTrait;

    const STATUS_ACTIVE   = 'active';
    const STATUS_DEACTIVE = 'deactive';

    /**
     * Offer constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->table = "offer";

        $this->primaryKey = 'id';
        $this->initUuid();

        $this->incrementing = false;

        $this->dates = [
            'dt_start',
            'dt_finish',
        ];

        $this->initAttributes();
        $this->initFillable();
        $this->initHidden();
        $this->initAppends();
        $this->initCasts();
        $this->initMaps();

        parent::__construct($attributes);
    }

    /**
     * The event map for the model Offers.
     *
     * @var array
     */
    protected $events = [
        'deleting' => OfferObserver::class,
    ];

    /**
     * @var array
     */
    public static $publicAttributes = [
        'id',
        'label',
        'description',
        'start_date',
        'finish_date',
        'country',
        'city',
        'category_id',
        'latitude',
        'longitude',
        'radius',
        'reserved',
        'points',
        'max_for_user',
        'max_per_day',
        'max_for_user_per_day',
        'max_for_user_per_week',
        'max_for_user_per_month',
        'max_count',
        'user_level_min',
    ];

    protected static function boot()
    {
        parent::boot();

        self::bootGlobalScopes();
    }

    /**
     * @return Account
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /** @return string */
    public function getId(): ?string
    {
        return $this->id;
    }

    /** @return int */
    public function getAccountId(): ?int
    {
        return $this->account_id;
    }

    /** @return string */
    public function getLabel(): string
    {
        return $this->label;
    }

    /** @return int */
    public function getPoints(): int
    {
        return $this->points;
    }

    /** @return string */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function getRewardAttribute(?int $value): float
    {
        return $this->convertIntToFloat((int)$value);
    }

    /**
     * @param float $value
     *
     * @return void
     */
    public function setRewardAttribute(?float $value): void
    {
        $this->attributes['reward'] = $this->convertFloatToInt((float)$value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function getReservedAttribute(?int $value): float
    {
        return $this->convertIntToFloat((int)$value);
    }

    /**
     * @param float $value
     *
     * @return void
     */
    public function setReservedAttribute(?float $value): void
    {
        $this->attributes['reserved'] = $this->convertFloatToInt((float)$value);
    }

    /** @return float */
    public function getReward(): ?float
    {
        return $this->reward;
    }

    /** @return float */
    public function getReserved(): float
    {
        return $this->reserved;
    }

    /** @return string */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /** @return Carbon */
    public function getStartDate(): ?Carbon
    {
        return $this->start_date;
    }

    /**
     * @return Carbon|null
     */
    public function getFinishDate(): ?Carbon
    {
        return $this->finish_date;
    }

    /** @return string */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /** @return string */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /** @return string */
    public function getCategoryId(): ?string
    {
        return $this->category_id;
    }

    /**
     * @return int|null
     */
    public function getMaxCount(): ?int
    {
        return $this->max_count;
    }

    /**
     * @return int|null
     */
    public function getMaxForUser(): ?int
    {
        return $this->max_for_user;
    }

    /**
     * @return int|null
     */
    public function getMaxPerDay(): ?int
    {
        return $this->max_per_day;
    }

    /**
     * @return int|null
     */
    public function getMaxForUserPerDay(): ?int
    {
        return $this->max_for_user_per_day;
    }

    /**
     * @return int|null
     */
    public function getMaxForUserPerWeek(): ?int
    {
        return $this->max_for_user_per_week;
    }

    /**
     * @return int|null
     */
    public function getMaxForUserPerMonth(): ?int
    {
        return $this->max_for_user_per_month;
    }

    /** @return int */
    public function getUserLevelMin(): int
    {
        return $this->user_level_min;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @return int|null
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        $account = $this->account;

        return $account === null ? null : $account->owner;
    }

    /**
     * @return null|string
     */
    public function getPictureUrlAttribute(): ?string
    {
        return route('offer.picture.show', ['offerId' => $this->id ?? 'empty']);
    }

    /**
     * @return int
     */
    public function getRedemptionsCount(): int
    {
        return $this->redemptions_count;
    }

    /**
     * @return int
     */
    public function getRedemptionsCountAttribute(): int
    {
        return $this->activationCodes()->whereNotNull('redemption_id')->count();
    }

    /**
     * Get the description with offer links
     *
     * @return string
     */
    public function getRichDescriptionAttribute(): string
    {
        $regex       = '|\B' . OfferLink::SPECIAL_SYMBOL . '(\w*)|';
        $description = (string)$this->description;

        preg_match_all($regex, $description, $matches);

        $tags = array_get($matches, 1);

        foreach ($tags as $tag) {
            $link = $this->getHtmlOfferLink($tag);

            if ($link instanceof HtmlString) {
                $description = str_replace(OfferLink::SPECIAL_SYMBOL . $tag, $link, $description);
            }
        }

        return $description;
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
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user): bool
    {
        return $user->equals($this->getOwner());
    }

    /**
     * @param string $status
     *
     * @return Offer
     */
    public function setStatus(string $status): Offer
    {
        $this->status = $status;

        return $this;
    }

    public function setIsFavoriteAttribute($isFavorite)
    {
        $this->attributes['is_favorite'] = $isFavorite;

        return $this;
    }

    /**
     * @param string $code
     *
     * @return Redemption
     * @throws BadActivationCodeException|CannotRedeemException
     */
    public function redeem(string $code): Redemption
    {
        $activationCode = $this->activationCodes()->byCode($code)->first();

        if (null === $activationCode) {
            throw new BadActivationCodeException($this, $code);
        }

        DB::beginTransaction();

        $redemption = $this->redemptions()->create(['user_id' => $activationCode->getUserId()]);

        if (null === $redemption->getId()) {
            DB::rollBack();

            throw new CannotRedeemException($this, $activationCode->getCode());
        }

        DB::commit();

        $activationCode->activated($redemption);

        return $redemption;
    }

    /**
     * @param string $tag
     * @return HtmlString|null
     */
    private function getHtmlOfferLink(string $tag): ?HtmlString
    {
        if (null === $this->getOwner()) {
            return null;
        }

        $place = $this->getOwner()->place;

        $offerLink = app('offerLinkRepository')
            ->scopePlace($place)
            ->findByField('tag', $tag)
            ->first();

        if (null === $offerLink) {
            return null;
        }

        $html = sprintf('<a href="%1$s">%2$s</a>',
            route('places.offer_links.show', [$place, $offerLink]),
            $offerLink->getTitle()
        );

        return new HtmlString($html);
    }

    private function initAttributes(): void
    {
        $defaultReward         = $this->convertFloatToInt(1);
        $reservationMultiplier = (int)config('nau.reservation_multiplier');

        $this->attributes = [
            'acc_id'                  => null,
            'name'                    => '',
            'descr'                   => '',
            'reward'                  => $defaultReward,
            'status'                  => null,
            'dt_start'                => null,
            'dt_finish'               => null,
            'country'                 => null,
            'city'                    => null,
            'categ'                   => null,
            'max_count'               => null,
            'max_for_user'            => null,
            'max_per_day'             => null,
            'max_for_user_per_day'    => null,
            'max_for_user_per_week'   => null,
            'max_for_user_per_month'  => null,
            'min_level'               => 1,
            'lat'                     => null,
            'lng'                     => null,
            'radius'                  => null,
            'reserved'                => $defaultReward * $reservationMultiplier,
            'points'                  => 1,
            'referral_points_price'   => 0,
            'redemption_points_price' => 0,
            'is_featured'             => false,
        ];
    }

    private function initFillable(): void
    {
        $this->fillable = [
            'account_id',
            'label',
            'description',
            'reward',
            'start_date',
            'finish_date',
            'country',
            'city',
            'category_id',
            'max_count',
            'max_for_user',
            'max_per_day',
            'max_for_user_per_day',
            'max_for_user_per_week',
            'max_for_user_per_month',
            'user_level_min',
            'latitude',
            'longitude',
            'radius',
            'status',
            'reserved',
            'points',
            'referral_points_price',
            'redemption_points_price',
            'is_featured',
        ];
    }

    private function initHidden(): void
    {
        $this->hidden = [
            'acc_id',
            'name',
            'descr',
            'dt_start',
            'dt_finish',
            'categ',
            'min_level',
            'lat',
            'lng',
            'offerData',
        ];
    }

    private function initAppends(): void
    {
        $this->appends = [
            'account_id',
            'label',
            'rich_description',
            'description',
            'start_date',
            'finish_date',
            'category_id',
            'user_level_min',
            'latitude',
            'longitude',
            'picture_url',
            'delivery',
            'type',
            'gift_bonus_descr',
            'discount_percent',
            'discount_start_price',
            'discount_finish_price',
            'currency',
            'timeframes_offset',
            'redemptions_count',
            'referral_points_price',
            'redemption_points_price',
            'is_featured',
        ];
    }

    private function initCasts(): void
    {
        $this->casts = [
            'id'                      => 'string',
            'account_id'              => 'integer',
            'label'                   => 'string',
            'description'             => 'string',
            'status'                  => 'string',
            'start_date'              => 'datetime',
            'finish_date'             => 'datetime',
            'country'                 => 'string',
            'city'                    => 'string',
            'category_id'             => 'string',
            'max_count'               => 'integer',
            'max_for_user'            => 'integer',
            'max_per_day'             => 'integer',
            'max_for_user_per_day'    => 'integer',
            'max_for_user_per_week'   => 'integer',
            'max_for_user_per_month'  => 'integer',
            'user_level_min'          => 'integer',
            'latitude'                => 'double',
            'longitude'               => 'double',
            'radius'                  => 'integer',
            'reward'                  => 'integer',
            'reserved'                => 'integer',
            'delivery'                => 'boolean',
            'type'                    => 'string',
            'gift_bonus_descr'        => 'string',
            'discount_percent'        => 'float',
            'discount_start_price'    => 'float',
            'currency'                => 'string',
            'points'                  => 'integer',
            'referral_points_price'   => 'integer',
            'redemption_points_price' => 'integer',
            'is_featured'             => 'boolean',
        ];
    }

    private function initMaps(): void
    {
        $this->maps = [
            'account_id'     => 'acc_id',
            'label'          => 'name',
            'description'    => 'descr',
            'start_date'     => 'dt_start',
            'finish_date'    => 'dt_finish',
            'category_id'    => 'categ',
            'user_level_min' => 'min_level',
            'latitude'       => 'lat',
            'longitude'      => 'lng'
        ];
    }
}
