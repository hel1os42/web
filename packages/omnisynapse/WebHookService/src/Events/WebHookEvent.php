<?php

namespace OmniSynapse\WebHookService\Events;

use App\Models\NauModels\ActivationCode;
use OmniSynapse\WebHookService\Contracts\Hookable;

abstract class WebHookEvent implements Hookable
{
    public const EVENT_NAME_OFFER_CREATED = 'offer_created';
    public const EVENT_NAME_OFFER_UPDATED = 'offer_updated';
    public const EVENT_NAME_OFFER_DELETED = 'offer_deleted';
    public const EVENT_NAME_CODE_CREATED  = 'activation_code_created';
    public const EVENT_NAME_CODE_UPDATED  = 'activation_code_updated';

    /**
     * @return string
     */
    abstract protected function getName(): string;

    protected function getCommonPayload()
    {
        return [
            'event_name' => $this->getName(),
        ];
    }
}