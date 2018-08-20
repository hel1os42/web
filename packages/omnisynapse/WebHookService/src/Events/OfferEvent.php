<?php

namespace OmniSynapse\WebHookService\Events;

use App\Models\NauModels\Offer;
use OmniSynapse\WebHookService\Presenters\OfferPresenter;

abstract class OfferEvent extends WebHookEvent
{
    /**
     * @var Offer
     */
    protected $offer;

    /**
     * OfferCreatedEvent constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPayload(): array
    {
        return array_merge($this->getCommonPayload(), (new OfferPresenter)->present($this->offer));
    }
}
