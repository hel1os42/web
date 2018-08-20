<?php

namespace OmniSynapse\WebHookService\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OmniSynapse\WebHookService\Events\WebHookEvent;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class WebHook
 * @package OmniSynapse\WebHookService\Models
 * @property User user
 */
class WebHook extends Model
{
    use Eloquence,
        Mappable;

    protected $table = 'webhooks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'url',
        'events',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id'     => 'string',
        'url'         => 'string',
        'event_names' => 'array',
    ];

    /**
     * Mapped attributes
     *
     * @var array
     */
    protected $maps = [
        'events' => 'event_names',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return WebHook
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param array $events
     * @return WebHook
     */
    public function setEvents(array $events): self
    {
        $this->event_names = $events;

        return $this;
    }

    /**
     * @param string $eventName
     * @return bool
     */
    public function hasEvent(string $eventName): bool
    {
        return in_array($eventName, $this->event_names);
    }

    /**
     * @return array
     */
    public static function getSupportedEventNames(): array
    {
        return [
            WebHookEvent::EVENT_NAME_OFFER_CREATED,
            WebHookEvent::EVENT_NAME_OFFER_UPDATED,
            WebHookEvent::EVENT_NAME_OFFER_DELETED,
            WebHookEvent::EVENT_NAME_CODE_CREATED,
            WebHookEvent::EVENT_NAME_CODE_UPDATED,
        ];
    }
}
