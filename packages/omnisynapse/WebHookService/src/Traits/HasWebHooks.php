<?php

namespace OmniSynapse\WebHookService\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use OmniSynapse\WebHookService\Models\WebHook;

/**
 * Trait HasWebHooks
 * @package OmniSynapse\WebHookService\Traits
 * @property WebHook[] webhooks
 */
trait HasWebHooks
{

    /**
     * Get the user referrals.
     *
     * @return HasMany
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(WebHook::class);
    }

    /**
     * @param string $eventName
     * @return Collection
     */
    public function getWebHooksByEventName(string $eventName): Collection
    {
        return $this->webhooks->filter(function (WebHook $webHook) use ($eventName) {
            logger($eventName);
            logger('hook', $webHook->toArray());
            logger('has'. (int)$webHook->hasEvent($eventName));
            return $webHook->hasEvent($eventName);
        });
    }
}
