<?php

namespace OmniSynapse\CoreService\Request;

/**
 * Class OfferForUpdate.
 * @package OmniSynapse\CoreService\Request
 */
class OfferForUpdate extends Offer implements \JsonSerializable
{
    /** @var string */
    public $offerId;

    /**
     * OfferForUpdate constructor.
     *
     * @param \App\Models\NauModels\Offer $offer
     *
     * @throws \OmniSynapse\CoreService\Exception\Exception
     */
    public function __construct(\App\Models\NauModels\Offer $offer)
    {
        parent::__construct($offer);
        
        $this->setOfferId($offer->getId());
    }

    /**
     * @param string $offerId
     * @return OfferForUpdate
     */
    public function setOfferId(string $offerId): OfferForUpdate
    {
        $this->offerId = $offerId;
        return $this;
    }
}
