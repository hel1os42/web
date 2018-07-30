<?php

namespace OmniSynapse\WebHookService\Observers;

use App\Models\NauModels\Offer;
use Illuminate\Support\Facades\DB;
use OmniSynapse\WebHookService\Contracts\WebHookService;
use OmniSynapse\WebHookService\Events\OfferCreatedEvent;
use OmniSynapse\WebHookService\Events\OfferDeletedEvent;
use OmniSynapse\WebHookService\Events\OfferUpdatedEvent;
use OmniSynapse\WebHookService\Events\WebHookEvent;
use OmniSynapse\WebHookService\Jobs\SendWebHook;

class OfferObserver
{
    /**
     * @var WebHookService
     */
    private $webHookService;

    /**
     * OfferObserver constructor.
     * @param WebHookService $webHookService
     */
    public function __construct(WebHookService $webHookService)
    {
        $this->webHookService = $webHookService;
    }

    /**
     * @param Offer $offer
     */
    public function created(Offer $offer)
    {
        $user = $offer->account->owner;

        $affectedWebHooks = $user->getWebHooksByEventName(WebHookEvent::EVENT_NAME_OFFER_CREATED);

        foreach ($affectedWebHooks as $webHook) {
            $event = new OfferCreatedEvent($offer);

            SendWebHook::dispatch($this->webHookService, $webHook->getUrl(), $event);
        }
    }

    /**
     * @param Offer $offer
     */
    public function updated(Offer $offer)
    {
        $user = $offer->account->owner;

        $affectedWebHooks = $user->getWebHooksByEventName(WebHookEvent::EVENT_NAME_OFFER_UPDATED);

        logger()->debug('LOG count ' .  $affectedWebHooks->count());
        logger()->debug('LOG', $affectedWebHooks->toArray());

        foreach ($affectedWebHooks as $webHook) {
            $event = new OfferUpdatedEvent($offer);

            SendWebHook::dispatch($this->webHookService, $webHook->getUrl(), $event);
        }
    }

    /**
     * @param Offer $offer
     */
    public function deleted(Offer $offer)
    {
        $user = $offer->account->owner;

        $affectedWebHooks = $user->getWebHooksByEventName(WebHookEvent::EVENT_NAME_OFFER_DELETED);

        foreach ($affectedWebHooks as $webHook) {
            $event = new OfferDeletedEvent($offer);

            SendWebHook::dispatch($this->webHookService, $webHook->getUrl(), $event);
        }
    }
}
