<?php

namespace OmniSynapse\WebHookService\Events;

use App\Models\ActivationCode;
use OmniSynapse\WebHookService\Presenters\ActivationCodePresenter;

abstract class ActivationCodeEvent extends WebHookEvent
{
    /**
     * @var ActivationCode
     */
    protected $activationCode;

    /**
     * ActivationCodeEvent constructor.
     * @param ActivationCode $offer
     */
    public function __construct(ActivationCode $offer)
    {
        $this->activationCode = $offer;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPayload(): array
    {
        return array_merge($this->getCommonPayload(), (new ActivationCodePresenter)->present($this->activationCode));
    }
}