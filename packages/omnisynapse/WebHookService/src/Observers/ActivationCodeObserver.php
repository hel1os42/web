<?php

namespace OmniSynapse\WebHookService\Observers;

use App\Models\ActivationCode;
use OmniSynapse\WebHookService\Contracts\WebHookService;
use OmniSynapse\WebHookService\Events\ActivationCodeCreatedEvent;
use OmniSynapse\WebHookService\Events\ActivationCodeUpdatedEvent;
use OmniSynapse\WebHookService\Events\WebHookEvent;
use OmniSynapse\WebHookService\Jobs\SendWebHook;

class ActivationCodeObserver
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
     * @param ActivationCode $code
     */
    public function created(ActivationCode $code)
    {
        $user = $code->offer->account->owner;

        $affectedWebHooks = $user->getWebHooksByEventName(WebHookEvent::EVENT_NAME_CODE_CREATED);

        foreach ($affectedWebHooks as $webHook) {
            $event = new ActivationCodeCreatedEvent($code);

            SendWebHook::dispatch($this->webHookService, $webHook->getUrl(), $event);
        }
    }

    /**
     * @param ActivationCode $code
     */
    public function updated(ActivationCode $code)
    {
        $user = $code->offer->account->owner;

        $affectedWebHooks = $user->getWebHooksByEventName(WebHookEvent::EVENT_NAME_CODE_UPDATED);

        foreach ($affectedWebHooks as $webHook) {
            $event = new ActivationCodeUpdatedEvent($code);

            SendWebHook::dispatch($this->webHookService, $webHook->getUrl(), $event);
        }
    }
}
